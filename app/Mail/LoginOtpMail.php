<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\SiteSetting;

class LoginOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otpCode;
    public $userType;
    public $expiresMinutes;
    public $siteName;

    /**
     * Create a new message instance.
     *
     * @param string $otpCode
     * @param string $userType
     * @param int $expiresMinutes
     */
    public function __construct($otpCode, $userType = 'candidate', $expiresMinutes = 10)
    {
        $this->otpCode = $otpCode;
        $this->userType = $userType;
        $this->expiresMinutes = $expiresMinutes;

        $siteSetting = SiteSetting::first();
        $this->siteName = $siteSetting ? $siteSetting->site_name : config('app.name', 'Jobs Portal');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Your Secure Login OTP: {$this->otpCode} - {$this->siteName}")
                    ->view('emails.login_otp');
    }
}
