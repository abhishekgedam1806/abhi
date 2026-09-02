<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessMedia extends Model
{
    protected $table = 'business_media';
    public $timestamps = true;
    protected $guarded = ['id'];

    public function business()
    {
        return $this->belongsTo(\App\Business::class, 'business_id', 'id');
    }

    public function getUrlAttribute()
    {
        return asset('business_images/' . $this->media_path);
    }
}
