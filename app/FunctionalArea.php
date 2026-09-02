<?php

namespace App;

use App;
use App\Traits\Lang;
use App\Traits\IsDefault;
use App\Traits\Active;
use App\Traits\Sorted;
use Illuminate\Database\Eloquent\Model;

class FunctionalArea extends Model
{

    use Lang;
    use IsDefault;
    use Active;
    use Sorted;

    protected $table = 'functional_areas';
    public $timestamps = true;
    protected $guarded = ['id'];
    //protected $dateFormat = 'U';
    protected $dates = ['created_at', 'updated_at'];

    public static function getUsingFunctionalAreas($limit = 10)
    {
        $functionalAreaIds = App\Job::select('functional_area_id')->whereNotNull('functional_area_id')->pluck('functional_area_id')->toArray();
        if (empty($functionalAreaIds)) {
            return App\FunctionalArea::lang()->active()->orderBy('functional_area', 'ASC')->limit($limit)->get();
        }
        return App\FunctionalArea::whereIn('functional_area_id', array_unique($functionalAreaIds))
            ->lang()
            ->active()
            ->orderBy('functional_area', 'ASC')
            ->limit($limit)
            ->get();
    }

    public function jobSkills()
    {
        return $this->hasMany('App\JobSkill', 'functional_area_id', 'functional_area_id');
    }

}
