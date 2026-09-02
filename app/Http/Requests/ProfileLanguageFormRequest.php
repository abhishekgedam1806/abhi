<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProfileLanguageFormRequest extends Request
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
        switch ($this->method()) {
            case 'PUT':
            case 'POST': {
                    // Multi-select: language_ids[] array (add mode)
                    if ($this->has('language_ids')) {
                        return [
                            "language_ids"       => "required|array|min:1",
                            "language_ids.*"     => "required|integer",
                            "language_level_ids" => "required|array|min:1",
                            "language_level_ids.*" => "required|integer",
                        ];
                    }
                    // Single edit mode (legacy)
                    return [
                        "language_id"       => "required",
                        "language_level_id" => "required",
                    ];
                }
            default:break;
        }
    }

    public function messages()
    {
        return [
            'language_ids.required'         => 'Please select at least one language.',
            'language_ids.min'              => 'Please select at least one language.',
            'language_level_ids.required'   => 'Please select proficiency level for each language.',
            'language_level_ids.*.required' => 'Please select proficiency level for each selected language.',
            'language_id.required'          => 'Please select language.',
            'language_level_id.required'    => 'Please select language level.',
        ];
    }

}
