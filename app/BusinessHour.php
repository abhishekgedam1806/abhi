<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessHour extends Model
{
    protected $table = 'business_hours';
    public $timestamps = true;
    protected $guarded = ['id'];

    public static function dayNames()
    {
        return [
            0 => 'Monday',
            1 => 'Tuesday',
            2 => 'Wednesday',
            3 => 'Thursday',
            4 => 'Friday',
            5 => 'Saturday',
            6 => 'Sunday',
        ];
    }

    public function getDayNameAttribute()
    {
        return self::dayNames()[$this->day] ?? 'Unknown';
    }

    public function business()
    {
        return $this->belongsTo(\App\Business::class, 'business_id', 'id');
    }
}
