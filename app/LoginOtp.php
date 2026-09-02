<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LoginOtp extends Model
{
    protected $table = 'login_otps';
    protected $guarded = ['id'];
    protected $dates = ['expires_at', 'created_at', 'updated_at'];

    public function isExpired()
    {
        return $this->expires_at->isPast() || $this->is_used;
    }

    public function isValid($code)
    {
        return !$this->isExpired() && (string)$this->otp_code === (string)$code && $this->attempts < 5;
    }
}
