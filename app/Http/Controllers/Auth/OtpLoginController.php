<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\LoginOtp;
use App\User;
use App\Company;
use App\Services\EmailFraudValidator;
use App\Mail\LoginOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OtpLoginController extends Controller
{
    /**
     * Helper: Format Masked Email (e.g. ab***@gmail.com)
     */
    private function maskEmail($email)
    {
        $parts = explode('@', $email);
        $namePart = $parts[0];
        $domainPart = isset($parts[1]) ? $parts[1] : '';
        $maskedName = strlen($namePart) <= 3 
            ? substr($namePart, 0, 1) . '***' 
            : substr($namePart, 0, 2) . str_repeat('*', max(strlen($namePart) - 3, 2)) . substr($namePart, -1);
        return $maskedName . '@' . $domainPart;
    }

    /**
     * Send 6-Digit Login / Verification OTP to Email with Anti-Fraud Validation.
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:191',
            'user_type' => 'required|string|in:candidate,employer,business',
        ]);

        $email = trim(strtolower($request->input('email')));
        $userType = $request->input('user_type');
        $ip = $request->ip();

        // 1. Anti-Fraud Email Validation
        $fraudCheck = EmailFraudValidator::validate($email);
        if (!$fraudCheck['valid']) {
            return response()->json([
                'status' => 'error',
                'message' => $fraudCheck['reason']
            ], 422);
        }

        // 2. Rate Limiting: Max 4 OTP requests in 10 minutes per Email
        $recentCount = LoginOtp::where('email', $email)
            ->where('created_at', '>=', Carbon::now()->subMinutes(10))
            ->count();

        if ($recentCount >= 4) {
            return response()->json([
                'status' => 'error',
                'message' => 'Too many OTP requests. For security, please wait 5 minutes before trying again.'
            ], 429);
        }

        // 3. Check if user/account exists BEFORE sending OTP (Login-only flow)
        if ($userType === 'employer') {
            // Employer: must have Company record
            $company = Company::where('email', $email)->first();
            if (!$company) {
                return response()->json([
                    'status' => 'error',
                    'message' => "❌ No Employer account found with this email. Please register your company first."
                ], 404);
            }
        } elseif ($userType === 'candidate') {
            // Candidate: must have User record
            $user = User::where('email', $email)->first();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => "❌ No account found with this email. Please register first as a Job Seeker."
                ], 404);
            }
        } elseif ($userType === 'business') {
            // Business: must have User record with business type
            $user = User::where('email', $email)->first();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => "❌ No Business account found with this email. Please register first."
                ], 404);
            }
        }

        // 4. Invalidate any existing unused OTPs for this email
        LoginOtp::where('email', $email)
            ->where('is_used', 0)
            ->update(['is_used' => 1]);

        // 5. Generate secure 6-digit numeric OTP (5 minutes validity)
        $otpCode = str_pad(mt_rand(100000, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = Carbon::now()->addMinutes(5);

        // 6. Save to login_otps table
        LoginOtp::create([
            'email' => $email,
            'otp_code' => $otpCode,
            'user_type' => $userType,
            'ip_address' => $ip,
            'user_agent' => $request->userAgent(),
            'attempts' => 0,
            'is_used' => 0,
            'expires_at' => $expiresAt,
        ]);

        // 7. Send OTP Email via configured SMTP
        try {
            Mail::to($email)->send(new LoginOtpMail($otpCode, $userType, 5));
        } catch (\Exception $e) {
            Log::error('OTP Mail Delivery Failed: ' . $e->getMessage());
        }

        $maskedEmail = $this->maskEmail($email);

        return response()->json([
            'status' => 'ok',
            'message' => "A 6-digit verification code has been sent to {$maskedEmail}.",
            'masked_email' => $maskedEmail,
            'cooldown' => 30
        ]);
    }

    /**
     * Verify 6-digit OTP and Log In / Activate Account.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:191',
            'otp_code' => 'required|string|min:6|max:6',
            'user_type' => 'required|string|in:candidate,employer,business',
        ]);

        $email = trim(strtolower($request->input('email')));
        $otpCode = trim($request->input('otp_code'));
        $userType = $request->input('user_type');

        // 1. Retrieve the latest active OTP
        $otpRecord = LoginOtp::where('email', $email)
            ->where('is_used', 0)
            ->where('expires_at', '>=', Carbon::now())
            ->orderBy('id', 'desc')
            ->first();

        if (!$otpRecord) {
            return response()->json([
                'status' => 'error',
                'message' => 'The OTP code is invalid or has expired. Please request a new code.'
            ], 422);
        }

        // 2. Check maximum failed attempts (Brute Force Protection)
        if ($otpRecord->attempts >= 5) {
            $otpRecord->update(['is_used' => 1]);
            return response()->json([
                'status' => 'error',
                'message' => 'Maximum verification attempts exceeded. Please request a fresh OTP code.'
            ], 429);
        }

        // 3. Verify OTP Code Match
        if ((string)$otpRecord->otp_code !== (string)$otpCode) {
            $otpRecord->increment('attempts');
            $remaining = 5 - $otpRecord->attempts;
            return response()->json([
                'status' => 'error',
                'message' => "Incorrect OTP code. {$remaining} attempts remaining."
            ], 422);
        }

        // 4. Mark OTP as used (Single-Use & Replay Protection)
        $otpRecord->update(['is_used' => 1]);

        // 5. Authenticate & Activate user into appropriate role & session
        if ($userType === 'employer') {
            $company = Company::where('email', $email)->first();
            if (!$company) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Employer account not found. Please register first.'
                ], 404);
            }
            
            // Mark company as verified and active
            $company->verified = 1;
            $company->is_active = 1;
            $company->save();

            Auth::guard('company')->login($company, true);
            $redirectUrl = route('company.home');

        } elseif ($userType === 'business') {
            $user = User::where('email', $email)->first();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Business account not found. Please register first.'
                ], 404);
            }

            $user->verified = 1;
            $user->is_active = 1;
            $user->user_type = 'business';
            $user->save();

            Auth::login($user, true);
            $redirectUrl = route('business.dashboard');

        } else {
            // Candidate / Job Seeker
            $user = User::where('email', $email)->first();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No account found with this email. Please register first as a Job Seeker.'
                ], 404);
            }

            $user->verified = 1;
            $user->is_active = 1;
            if ($user->user_type !== 'business') {
                $user->user_type = 'jobseeker';
            }
            $user->save();

            Auth::login($user, true);
            if (!(bool)$user->onboarding_completed) {
                $redirectUrl = route('onboarding');
            } else {
                $redirectUrl = route('home');
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Verification successful! Redirecting to your dashboard...',
            'redirect_url' => $redirectUrl
        ]);
    }

    /**
     * Candidate Registration with Mandatory Email OTP
     */
    public function registerCandidate(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|email|max:191',
            'password' => 'required|string|min:6|max:50|confirmed',
            'terms_of_use' => 'required',
        ]);

        $email = trim(strtolower($request->input('email')));
        $existing = User::where('email', $email)->first();

        if ($existing && $existing->verified == 1 && $existing->is_active == 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'An account with this email already exists and is active. Please proceed to Login.'
            ], 422);
        }

        // Create or update pending unverified record
        if ($existing) {
            $user = $existing;
        } else {
            $user = new User();
        }

        $user->first_name = $request->input('first_name');
        $user->middle_name = $request->input('middle_name');
        $user->last_name = $request->input('last_name');
        $user->name = trim($request->input('first_name') . ' ' . $request->input('last_name'));
        $user->email = $email;
        $user->password = bcrypt($request->input('password'));
        $user->user_type = 'jobseeker';
        $user->verified = 0;   // UNVERIFIED UNTIL OTP IS ENTERED
        $user->is_active = 0;  // INACTIVE UNTIL OTP IS ENTERED
        $user->save();

        // Dispatch OTP
        return $this->sendRegistrationOtpHelper($email, 'candidate');
    }

    /**
     * Employer Registration with Mandatory Email OTP
     */
    public function registerEmployer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|string|email|max:191',
            'password' => 'required|string|min:6|max:50|confirmed',
            'terms_of_use' => 'required',
        ]);

        $email = trim(strtolower($request->input('email')));
        $existing = Company::where('email', $email)->first();

        if ($existing && $existing->verified == 1 && $existing->is_active == 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'An employer account with this email already exists and is active. Please proceed to Login.'
            ], 422);
        }

        // Create or update pending unverified record
        if ($existing) {
            $company = $existing;
        } else {
            $company = new Company();
        }

        $company->name = $request->input('name');
        $company->email = $email;
        $company->password = bcrypt($request->input('password'));
        $company->verified = 0;   // UNVERIFIED UNTIL OTP IS ENTERED
        $company->is_active = 0;  // INACTIVE UNTIL OTP IS ENTERED
        $company->save();

        $company->slug = Str::slug($company->name, '-') . '-' . $company->id;
        $company->save();

        // Dispatch OTP
        return $this->sendRegistrationOtpHelper($email, 'employer');
    }

    /**
     * Business Owner Registration with Mandatory Email OTP
     */
    public function registerBusiness(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|email|max:191',
            'phone' => 'nullable|string|max:25',
            'password' => 'required|string|min:6|max:50|confirmed',
            'terms_of_use' => 'required',
        ]);

        $email = trim(strtolower($request->input('email')));
        $existing = User::where('email', $email)->first();

        if ($existing && $existing->verified == 1 && $existing->is_active == 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'An account with this email already exists and is active. Please proceed to Login.'
            ], 422);
        }

        // Create or update pending unverified record
        if ($existing) {
            $user = $existing;
        } else {
            $user = new User();
        }

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->name = trim($request->input('first_name') . ' ' . $request->input('last_name'));
        $user->email = $email;
        if ($request->filled('phone')) {
            $user->phone = $request->input('phone');
        }
        $user->password = bcrypt($request->input('password'));
        $user->user_type = 'business';
        $user->verified = 0;   // UNVERIFIED UNTIL OTP IS ENTERED
        $user->is_active = 0;  // INACTIVE UNTIL OTP IS ENTERED
        $user->save();

        // Dispatch OTP
        return $this->sendRegistrationOtpHelper($email, 'business');
    }

    /**
     * Helper to issue and dispatch OTP for registration verification
     */
    private function sendRegistrationOtpHelper($email, $userType)
    {
        // Invalidate prior unused OTPs
        LoginOtp::where('email', $email)
            ->where('is_used', 0)
            ->update(['is_used' => 1]);

        $otpCode = str_pad(mt_rand(100000, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = Carbon::now()->addMinutes(5);

        LoginOtp::create([
            'email' => $email,
            'otp_code' => $otpCode,
            'user_type' => $userType,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'attempts' => 0,
            'is_used' => 0,
            'expires_at' => $expiresAt,
        ]);

        try {
            Mail::to($email)->send(new LoginOtpMail($otpCode, $userType, 5));
        } catch (\Exception $e) {
            Log::error('Registration OTP Delivery Failed: ' . $e->getMessage());
        }

        $maskedEmail = $this->maskEmail($email);

        return response()->json([
            'status' => 'otp_sent',
            'email' => $email,
            'masked_email' => $maskedEmail,
            'message' => "We've sent a 6-digit verification code to: {$maskedEmail}",
            'cooldown' => 30
        ]);
    }
}
