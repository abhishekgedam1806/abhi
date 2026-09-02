<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlockedEmailDomain extends Model
{
    protected $table = 'blocked_email_domains';
    protected $guarded = ['id'];
}
