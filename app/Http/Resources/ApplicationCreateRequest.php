<?php

namespace App\Http\Requests\application;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required|string',
            'subject'=>'required|string',
            'subject_type'=>'required|string',
            'subject_definition'=>'nullable|string',
            'subject_document'=>'nullable|integer|exists:files,id',
            'staffs'=>'required|array|exists:staff,id',
            'scope_and_purpose'=>'required|string',
            'error_or_broken'=>'required|string',
            'devices'=>'nullable|array|exists:devices,id',
            'techniques'=>'nullable|array|exists:devices,id',
            'license'=>'nullable|string',
            'certificate'=>'nullable|string|',
            'telecommunications'=>'required|array|exists:telecommunications,id',
            'provide_cyber_security'=>'required|string',
            'threats_to_information_security'=>'required|string',
            'consequences_of_an_incident'=>'required|string',
            'organizational_and_technical_measures_to_ensure_security'=>'required|string',

        ];
    }
}
