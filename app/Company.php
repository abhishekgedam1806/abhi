<?php



namespace App;



use Auth;

use App;

use Carbon\Carbon;

use App\Traits\Active;

use App\Traits\Featured;

use App\Traits\JobTrait;

use App\Traits\CountryStateCity;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;

use App\Notifications\CompanyResetPassword;

use Illuminate\Foundation\Auth\User as Authenticatable;



class Company extends Authenticatable

{



    use Active;

    use Featured;

    use Notifiable;

    use JobTrait;

    use CountryStateCity;



    protected $table = 'companies';

    public $timestamps = true;

    protected $guarded = ['id'];

    //protected $dateFormat = 'U';

    protected $dates = ['created_at', 'updated_at', 'package_start_date', 'package_end_date'];

    protected $fillable = [

        'name', 'email', 'password',

    ];

    protected $hidden = [

        'password', 'remember_token',

    ];



    public function sendPasswordResetNotification($token)

    {

        $this->notify(new CompanyResetPassword($token));

    }



    public function printCompanyImage($width = 0, $height = 0)

    {

        $logo = (string)$this->logo;

        $logo = (null!==($logo)) ? $logo : 'no-no-image.gif';
        return \ImgUploader::print_image("company_logos/$logo", $width, $height, '/admin_assets/no-image.png', $this->name);

    }



    public function jobs()

    {

        return $this->hasMany('App\Job', 'company_id', 'id');

    }



    public function openJobs()

    {

        return Job::where('company_id', '=', $this->id)->notExpire();

    }



    public function getOpenJobs()

    {

        return $this->openJobs()->get();

    }



    public function countOpenJobs()

    {

        return $this->openJobs()->count();

    }



    public function industry()

    {

        return $this->belongsTo('App\Industry', 'industry_id', 'id');

    }



    public function getIndustry($field = '')

    {

        $industry = $this->industry()->lang()->first();

        if (null === $industry) {

            $industry = $this->industry()->first();

        }

        if (null !== $industry) {

            if (!empty($field)) {

                return $industry->$field;

            } else {

                return $industry;

            }

        }

    }



    public function ownershipType()

    {

        return $this->belongsTo('App\OwnershipType', 'ownership_type_id', 'id');

    }



    public function getOwnershipType($field = '')

    {

        $ownershipType = $this->ownershipType()->lang()->first();

        if (null === $ownershipType) {

            $ownershipType = $this->ownershipType()->first();

        }

        if (null !== $ownershipType) {

            if (!empty($field)) {

                return $ownershipType->$field;

            } else {

                return $ownershipType;

            }

        }

    }



    public function countFollowers()

    {

        return FavouriteCompany::where('company_slug', 'like', $this->slug)->count();

    }



    public function getFollowerIdsArray()

    {

        return FavouriteCompany::where('company_slug', 'like', $this->slug)->pluck('user_id')->toArray();

    }



    public function countCompanyMessages()

    {

        return CompanyMessage::where('company_id', '=', $this->id)->where('status', '=', 'unviewed')->where('type', '=', 'reply')->count();

    }

    public function countMessages($id)

    {

        return CompanyMessage::where('company_id', '=', $this->id)->where('seeker_id', '=', $id)->where('type', 'reply')->where('status', '=', 'unviewed')->count();

    }



    public function getSocialNetworkHtml()

    {

        $html = '';

        if (!empty($this->facebook))

            $html .= '<a href="' . $this->facebook . '" target="_blank"><i class="fa fa-facebook-square" aria-hidden="true"></i></a>';



        if (!empty($this->twitter))

            $html .= '<a href="' . $this->twitter . '" target="_blank"><i class="fa fa-twitter-square" aria-hidden="true"></i></a>';



        if (!empty($this->linkedin))

            $html .= '<a href="' . $this->linkedin . '" target="_blank"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a>';



        if (!empty($this->google_plus))

            $html .= '<a href="' . $this->google_plus . '" target="_blank"><i class="fa fa-google-plus-square" aria-hidden="true"></i></a>';



        if (!empty($this->pinterest))

            $html .= '<a href="' . $this->pinterest . '" target="_blank"><i class="fa fa-pinterest-square" aria-hidden="true"></i></a>';



        return $html;

    }



    public function isFavouriteApplicant($user_id, $job_id, $company_id)

    {

        $return = false;

        if (Auth::guard('company')->check()) {

            $count = FavouriteApplicant::where('user_id', $user_id)

                ->where('job_id', $job_id)

                ->where('company_id', $company_id)

                ->count();

            if ($count > 0)

                $return = true;

        }

        return $return;

    }



    public function package()

    {

        return $this->hasOne('App\Package', 'id', 'package_id');

    }



    public function getPackage($field = '')

    {

        $package = $this->package()->first();

        if (null !== $package) {
            if (!empty($field)) {
                return $package->$field;
            } else {
                return $package;
            }
        }
    }

    public function getHrName()
    {
        $dummyNames = ['Alfaz', 'Lagan Saluja', 'Priya Sharma', 'Rohit Verma', 'Ananya Deshmukh', 'Vikram Malhotra'];

        // 1. If explicit non-dummy hr_name is set
        if (!empty($this->hr_name) && !in_array(trim($this->hr_name), $dummyNames)) {
            return $this->hr_name;
        }

        // 2. Use CEO (the actual person who created the company and posted the job)
        if (!empty($this->ceo) && !in_array(trim($this->ceo), $dummyNames)) {
            return $this->ceo;
        }

        // 3. If hr_name exists
        if (!empty($this->hr_name)) {
            return $this->hr_name;
        }

        // 4. Fallback to Company Name
        return $this->name ?: 'Hiring Manager';
    }

    public function getHrAvatar()
    {
        if (!empty($this->hr_avatar) && file_exists(public_path('hr_avatars/' . $this->hr_avatar))) {
            return asset('hr_avatars/' . $this->hr_avatar);
        }
        return null;
    }

    public function getHrPhone()
    {
        return $this->phone ?: '';
    }

    public function getCleanPhone()
    {
        return preg_replace('/[^0-9+]/', '', $this->getHrPhone());
    }

    public function getHrWhatsapp()
    {
        return $this->whatsapp_number ?: $this->phone;
    }

    public function getCleanWhatsapp()
    {
        $clean = preg_replace('/[^0-9]/', '', $this->getHrWhatsapp());
        // Ensure country code if missing
        if (strlen($clean) === 10) {
            $clean = '91' . $clean;
        }
        return $clean;
    }

    public function canCallHr()
    {
        return (bool)$this->allow_phone_contact && !empty($this->getCleanPhone());
    }

    public function canWhatsappHr()
    {
        return (bool)$this->allow_whatsapp_contact && !empty($this->getCleanWhatsapp());
    }

}

