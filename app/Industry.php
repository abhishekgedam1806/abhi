<?php

namespace App;

use App;
use App\Traits\Lang;
use App\Traits\IsDefault;
use App\Traits\Active;
use App\Traits\Sorted;
use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{

    use Lang;
    use IsDefault;
    use Active;
    use Sorted;

    protected $table = 'industries';
    public $timestamps = true;
    protected $guarded = ['id'];
    //protected $dateFormat = 'U';
    protected $dates = ['created_at', 'updated_at'];

    public static function getUsingIndustries($limit = 10)
    {
        $companyIds = App\Job::select('company_id')->whereNotNull('company_id')->pluck('company_id')->toArray();
        $industryIds = App\Company::select('industry_id')->whereIn('id', $companyIds)->whereNotNull('industry_id')->pluck('industry_id')->toArray();
        if (empty($industryIds)) {
            return App\Industry::lang()->active()->orderBy('industry', 'ASC')->limit($limit)->get();
        }
        return App\Industry::whereIn('industry_id', array_unique($industryIds))
            ->lang()
            ->active()
            ->orderBy('industry', 'ASC')
            ->limit($limit)
            ->get();
    }

}
