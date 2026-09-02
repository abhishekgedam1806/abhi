<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessCategory extends Model
{
    protected $table = 'business_categories';
    public $timestamps = true;
    protected $guarded = ['id'];

    public function businesses()
    {
        return $this->hasMany(\App\Business::class, 'category_id', 'id')->where('is_active', 1);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
    }
}
