<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProfileCvFormRequest extends Request
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
                    $id = (int) $this->input('id', 0);
                    $cv_file = ($id > 0) ? 'nullable|' : 'required|';
                    return [
                        "title" => "nullable|max:255",
                        "is_default" => "nullable",
                        "cv_file" => $cv_file . 'mimes:doc,docx,docm,zip,pdf',
                    ];
                }
            default:break;
        }
    }

    public function messages()
    {
        return [
            'cv_file.required' => 'Please select your CV/Resume file (PDF, DOC, DOCX).',
            'cv_file.mimes' => 'Only PDF, DOC, DOCX and ZIP files are allowed.',
        ];
    }

}
