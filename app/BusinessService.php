<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessService extends Model
{
    protected $table = 'business_services';
    public $timestamps = true;
    protected $guarded = ['id'];

    public function business()
    {
        return $this->belongsTo(\App\Business::class, 'business_id', 'id');
    }
}
