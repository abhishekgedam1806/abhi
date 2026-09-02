<?php

namespace App;

use App;
use App\Traits\Lang;
use App\Traits\IsDefault;
use App\Traits\Active;
use App\Traits\Sorted;
use Illuminate\Database\Eloquent\Model;

class JobSkill extends Model
{

    use Lang;
    use IsDefault;
    use Active;
    use Sorted;

    protected $table = 'job_skills';
    public $timestamps = true;
    protected $guarded = ['id'];
    //protected $dateFormat = 'U';
    protected $dates = ['created_at', 'updated_at'];

    public function functionalArea()
    {
        return $this->belongsTo('App\FunctionalArea', 'functional_area_id', 'functional_area_id');
    }

    public function getFunctionalArea($field = 'functional_area')
    {
        $functionalArea = $this->functionalArea;
        if (null !== $functionalArea) {
            return $functionalArea->$field;
        }
        return '';
    }

}
