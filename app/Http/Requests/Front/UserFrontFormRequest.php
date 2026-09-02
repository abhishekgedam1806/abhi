<?php

namespace App\Http\Requests\Front;

use Auth;
use App\Http\Requests\Request;

class UserFrontFormRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = Auth::user()->id;
        $id_str = ',' . $id;
        return [
            'first_name' => 'required|max:80',
            'middle_name' => 'nullable|max:80',
            'last_name' => 'nullable|max:80',
            'email' => 'required|email|max:100|unique:users,email' . $id_str,
            'password' => 'nullable|max:50',
            'father_name' => 'nullable|max:80',
            'date_of_birth' => 'nullable|date',
            'gender_id' => 'nullable',
            'marital_status_id' => 'nullable',
            'nationality_id' => 'nullable',
            'national_id_card_number' => 'nullable|max:80',
            'country_id' => 'nullable',
            'state_id' => 'nullable',
            'city_id' => 'nullable',
            'phone' => 'nullable|max:20',
            'mobile_num' => 'nullable|max:22',
            'job_experience_id' => 'nullable',
            'career_level_id' => 'nullable',
            'industry_id' => 'nullable',
            'functional_area_id' => 'nullable',
            'current_salary' => 'nullable|max:20',
            'expected_salary' => 'nullable|max:20',
            'salary_currency' => 'nullable|max:10',
            'street_address' => 'nullable|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => __('First Name is required'),
            //'middle_name.required' => __('Middle Name is required'),
            //'last_name.required' => __('Last Name is required'),
            'email.required' => __('Email is required'),
            'email.email' => __('The email must be a valid email address'),
            'email.unique' => __('This Email has already been taken'),
            'password.required' => __('Password is required'),
            'password.min' => __('The password should be more than 3 characters long'),
            //'father_name.required' => __('Father Name is required'),
            'date_of_birth.required' => __('Date of birth is required'),
            //'gender_id.required' => __('Please select gender'),
           // 'marital_status_id.required' => __('Please select marital status'),
            'nationality_id.required' => __('Please select nationality'),
            //'national_id_card_number.required' => __('National ID card# required'),
            'country_id.required' => __('Please select country'),
            'state_id.required' => __('Please select state'),
            'city_id.required' => __('Please select city'),
            'phone.required' => __('Please enter phone'),
            //'mobile_num.required' => __('Please enter mobile number'),
           // 'job_experience_id.required' => __('Please select experience'),
           // 'career_level_id.required' => __('Please select career level'),
            'industry_id.required' => __('Please select industry'),
            'functional_area_id.required' => __('Please select functional area'),
           // 'current_salary.required' => __('Please enter current salary'),
           // 'expected_salary.required' => __('Please enter expected salary'),
           // 'salary_currency.required' => __('Please select salary currency'),
            'street_address.required' => __('Please enter street address'),
            'image.image' => __('Only images can be uploaded'),
        ];
    }

}
