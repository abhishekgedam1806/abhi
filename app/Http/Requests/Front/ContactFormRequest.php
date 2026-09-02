<?php

namespace App\Http\Requests\Front;

use App\Http\Requests\Request;

class ContactFormRequest extends Request
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
        $rules = [
            'full_name' => 'required|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'nullable|max:20',
            'subject' => 'required|max:200',
            'message_txt' => 'required',
        ];

        $siteSetting = \App\SiteSetting::first();
        if ($siteSetting && !empty($siteSetting->nocaptcha_sitekey) && !empty($siteSetting->nocaptcha_secret)) {
            $rules['g-recaptcha-response'] = 'nullable|captcha';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'full_name.required' => __('Name is required'),
            'email.required' => __('E-mail address is required'),
            'email.email' => __('Valid e-mail address is required'),
            'subject.required' => __('Subject is required'),
            'message_txt.required' => __('Message is required'),
        ];
    }

}
