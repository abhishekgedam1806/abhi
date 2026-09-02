<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Requests\Front\UserFrontRegisterFormRequest;
use Illuminate\Auth\Events\Registered;
use App\Events\UserRegistered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/onboarding';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Handle Candidate & Business User Registration
     */
    public function register(UserFrontRegisterFormRequest $request)
    {
        $email = strtolower(trim($request->input('email')));
        $userType = ($request->input('candidate_or_employer') === 'business' || $request->input('redirect_to') === 'business') ? 'business' : 'jobseeker';

        // Check if user already exists
        $existing = User::where('email', $email)->first();
        if ($existing) {
            flash(__('An account with this email address already exists. Please log in.'))->error();
            return redirect()->route('login');
        }

        $user = new User();
        $user->first_name = $request->input('first_name');
        $user->middle_name = $request->input('middle_name');
        $user->last_name = $request->input('last_name');
        $user->email = $email;
        $user->password = Hash::make($request->input('password'));
        $user->user_type = $userType;
        $user->is_active = 1;
        $user->verified = 1; // Mark verified on registration completion
        $user->onboarding_completed = 0;
        $user->onboarding_step = 1;
        $user->save();

        $user->name = $user->getName();
        $user->update();

        event(new Registered($user));
        event(new UserRegistered($user));

        // Auto login
        Auth::login($user, true);

        if ($userType === 'business') {
            return redirect()->route('business.dashboard');
        }

        // Direct candidate straight to the progressive 12-screen onboarding
        return redirect()->route('onboarding');
    }

    /**
     * Verification error route
     */
    public function getVerificationError()
    {
        return view('auth.verification_error');
    }

    /**
     * Verification token check route
     */
    public function getVerification(Request $request, $token)
    {
        $user = User::where('verification_token', $token)->first();
        if ($user) {
            $user->verified = 1;
            $user->verification_token = null;
            $user->save();
            Auth::login($user, true);
            flash(__('Your email has been verified successfully!'))->success();
            return redirect()->route('onboarding');
        }

        flash(__('Invalid or expired verification link.'))->error();
        return redirect()->route('login');
    }
}
