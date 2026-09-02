<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessLead extends Model
{
    protected $table = 'business_leads';
    public $timestamps = true;
    protected $guarded = ['id'];

    public function business()
    {
        return $this->belongsTo(\App\Business::class, 'business_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id', 'id');
    }
}
