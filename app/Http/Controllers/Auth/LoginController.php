<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Company;
use Auth;
use Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Send Email OTP for Login / Instant Auth
     */
    public function sendLoginOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:191',
            'user_type' => 'nullable|string|in:candidate,employer,business'
        ]);

        $email = strtolower(trim($request->input('email')));
        $userType = $request->input('user_type', 'candidate');
        $ip = $request->ip();

        // 1. Rate Limiting: Max 5 OTP requests per 10 minutes per IP/email
        $throttleKey = 'otp-send:' . md5($email . '|' . $ip);
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return response()->json([
                'status' => 'error',
                'message' => "Too many OTP requests. Please wait {$seconds} seconds before trying again."
            ], 429);
        }
        RateLimiter::hit($throttleKey, 600);

        // 2. Filter blocked / disposable domains
        $domain = substr(strrchr($email, "@"), 1);
        $isBlocked = DB::table('blocked_email_domains')->where('domain', $domain)->where('is_active', 1)->exists();
        if ($isBlocked) {
            return response()->json([
                'status' => 'error',
                'message' => 'Disposable email addresses are not permitted. Please use a valid email.'
            ], 422);
        }

        // 3. Invalidate all previous un-used OTPs for this email
        DB::table('login_otps')
            ->where('email', $email)
            ->where('is_used', 0)
            ->update(['is_used' => 1]);

        // 4. Generate 6-digit cryptographically secure OTP
        $otpCode = (string)random_int(100000, 999999);

        // 5. Store in login_otps table (10 minutes expiry)
        DB::table('login_otps')->insert([
            'email' => $email,
            'otp_code' => $otpCode,
            'user_type' => $userType,
            'ip_address' => $ip,
            'user_agent' => substr((string)$request->userAgent(), 0, 500),
            'attempts' => 0,
            'is_used' => 0,
            'expires_at' => date('Y-m-d H:i:s', time() + 600),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // 6. Send OTP Email
        try {
            Mail::raw("Your Job Portal login verification code is: {$otpCode}\n\nThis OTP is valid for 10 minutes. Please do not share it with anyone.", function ($message) use ($email) {
                $message->to($email)
                    ->subject('Your Login OTP - Job Portal');
            });
        } catch (\Exception $e) {
            // Log mail exception if SMTP fails, continue for test/local
            \Log::info("OTP generated for {$email}: {$otpCode}");
        }

        return response()->json([
            'status' => 'success',
            'message' => 'A 6-digit OTP has been sent to your email.',
            'cooldown_seconds' => 60
        ]);
    }

    /**
     * Verify OTP & Authenticate User
     */
    public function verifyLoginOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:191',
            'otp' => 'required|string|size:6',
            'user_type' => 'nullable|string|in:candidate,employer,business'
        ]);

        $email = strtolower(trim($request->input('email')));
        $otpCode = trim($request->input('otp'));
        $userType = $request->input('user_type', 'candidate');
        $ip = $request->ip();

        // Rate limiting for verify attempts: max 6 attempts per 5 mins
        $verifyThrottleKey = 'otp-verify:' . md5($email . '|' . $ip);
        if (RateLimiter::tooManyAttempts($verifyThrottleKey, 6)) {
            $seconds = RateLimiter::availableIn($verifyThrottleKey);
            return response()->json([
                'status' => 'error',
                'message' => "Too many incorrect attempts. Please wait {$seconds} seconds."
            ], 429);
        }

        // Find active valid OTP
        $otpRecord = DB::table('login_otps')
            ->where('email', $email)
            ->where('is_used', 0)
            ->where('expires_at', '>', date('Y-m-d H:i:s'))
            ->orderBy('id', 'desc')
            ->first();

        if (!$otpRecord) {
            RateLimiter::hit($verifyThrottleKey, 300);
            return response()->json([
                'status' => 'error',
                'message' => 'The OTP code is invalid or has expired. Please request a new code.'
            ], 422);
        }

        if ($otpRecord->attempts >= 5) {
            DB::table('login_otps')->where('id', $otpRecord->id)->update(['is_used' => 1]);
            return response()->json([
                'status' => 'error',
                'message' => 'Too many invalid attempts. Please request a fresh OTP.'
            ], 422);
        }

        // Check OTP match
        if (!hash_equals((string)$otpRecord->otp_code, (string)$otpCode)) {
            DB::table('login_otps')->where('id', $otpRecord->id)->increment('attempts');
            RateLimiter::hit($verifyThrottleKey, 300);
            return response()->json([
                'status' => 'error',
                'message' => 'Incorrect OTP code. Please check and try again.'
            ], 422);
        }

        // Mark OTP as used
        DB::table('login_otps')->where('id', $otpRecord->id)->update([
            'is_used' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        RateLimiter::clear($verifyThrottleKey);

        // Role-Based User Authentication & Creation
        $redirectUrl = route('home');

        if ($userType === 'employer') {
            // Employer Login via Company guard
            $company = Company::where('email', $email)->first();
            if (!$company) {
                $company = new Company();
                $company->name = explode('@', $email)[0];
                $company->email = $email;
                $company->password = Hash::make(str_random(16));
                $company->is_active = 1;
                $company->verified = 1;
                $company->save();
            }
            Auth::guard('company')->login($company, true);
            $redirectUrl = route('company.home');
        } elseif ($userType === 'business') {
            // Business Owner Login
            $user = User::where('email', $email)->first();
            if (!$user) {
                $user = new User();
                $user->name = explode('@', $email)[0];
                $user->email = $email;
                $user->password = Hash::make(str_random(16));
                $user->user_type = 'business';
                $user->is_active = 1;
                $user->verified = 1;
                $user->save();
            } else {
                $user->user_type = 'business';
                $user->verified = 1;
                $user->is_active = 1;
                $user->save();
            }
            Auth::login($user, true);
            $redirectUrl = route('business.dashboard');
        } else {
            // Candidate Login
            $user = User::where('email', $email)->first();
            if (!$user) {
                $user = new User();
                $nameParts = explode('@', $email)[0];
                $user->first_name = $nameParts;
                $user->name = $nameParts;
                $user->email = $email;
                $user->password = Hash::make(str_random(16));
                $user->user_type = 'jobseeker';
                $user->is_active = 1;
                $user->verified = 1;
                $user->onboarding_completed = 0;
                $user->onboarding_step = 1;
                $user->save();
            } else {
                $user->verified = 1;
                $user->is_active = 1;
                $user->save();
            }
            Auth::login($user, true);

            // If onboarding is incomplete, redirect directly to onboarding
            if (!(bool)$user->onboarding_completed) {
                $redirectUrl = route('onboarding');
            } else {
                $redirectUrl = route('home');
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged in!',
            'redirect_url' => $redirectUrl
        ]);
    }

    /**
     * Password Login override to support Candidate, Employer, and Business Owner
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Rate limiting for password login
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $email = strtolower(trim($request->input('email')));
        $password = $request->input('password');
        $userType = $request->input('candidate_or_employer', 'candidate');

        if ($userType === 'employer') {
            // Attempt Company Guard
            if (Auth::guard('company')->attempt(['email' => $email, 'password' => $password], $request->filled('remember'))) {
                $company = Auth::guard('company')->user();
                if ($company->is_active == 0) {
                    Auth::guard('company')->logout();
                    flash(__('Your account is inactive. Please contact support.'))->error();
                    return redirect()->route('login', ['tab' => 'employer']);
                }
                return redirect()->intended(route('company.home'));
            }
        } else {
            // Attempt User (Candidate / Business)
            if (Auth::attempt(['email' => $email, 'password' => $password], $request->filled('remember'))) {
                $user = Auth::user();

                if ($user->is_active == 0) {
                    Auth::logout();
                    flash(__('Your account is disabled. Please contact support.'))->error();
                    return redirect()->route('login');
                }

                if ($userType === 'business' || $user->user_type === 'business') {
                    return redirect()->route('business.dashboard');
                }

                // If candidate onboarding is incomplete, route to onboarding
                if ($user->isJobSeeker() && !(bool)$user->onboarding_completed) {
                    return redirect()->route('onboarding');
                }

                return redirect()->intended($this->redirectPath());
            }
        }

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Redirect the user to the OAuth Provider.
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider.
     */
    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
            $authUser = $this->findOrCreateUser($user, $provider);
            Auth::login($authUser, true);

            if ($authUser->isJobSeeker() && !(bool)$authUser->onboarding_completed) {
                return redirect()->route('onboarding');
            }

            return redirect($this->redirectTo);
        } catch (\Exception $e) {
            flash(__('Social authentication failed. Please try logging in with OTP or password.'))->error();
            return redirect()->route('login');
        }
    }

    /**
     * Find or create social user
     */
    public function findOrCreateUser($user, $provider)
    {
        $authUser = User::where('email', 'like', $user->email)->first();
        if ($authUser) {
            $authUser->provider = $provider;
            $authUser->provider_id = $user->id;
            $authUser->verified = 1;
            $authUser->is_active = 1;
            $authUser->save();
            return $authUser;
        }
        
        $newUser = new User();
        $newUser->first_name = $user->name;
        $newUser->name = $user->name;
        $newUser->email = $user->email;
        $newUser->provider = $provider;
        $newUser->provider_id = $user->id;
        $newUser->is_active = 1;
        $newUser->verified = 1;
        $newUser->user_type = 'jobseeker';
        $newUser->onboarding_completed = 0;
        $newUser->onboarding_step = 1;
        $newUser->save();
        return $newUser;
    }

    /**
     * Log the user out of the application and redirect to login page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        return redirect()->route('login');
    }
}
