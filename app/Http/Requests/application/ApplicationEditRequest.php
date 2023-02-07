<?php

namespace App\Http\Requests\application;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationEditRequest extends FormRequest
{

    public function rules()
    {
        return [
            'name'=>'string',
            'subject'=>'string',
            'subject_type'=>'string',
            'subject_definition'=>'string',
            'subject_document'=>'nullable|array|exists:files,id',
            'staffs'=>'required|array|exists:staff,id',
            'scope_and_purpose'=>'string',
            'error_or_broken'=>'string',
            'devices'=>'nullable|array|exists:devices,id',
            'license_id'=>'nullable|integer|exists:files,id',
            'certificate_id'=>'nullable|integer|exists:files,id',
            'telecommunications'=>'array|exists:telecommunications,id',
            'provide_cyber_security'=>'string',
            'threats_to_information_security'=>'string',
            'consequences_of_an_incident'=>'string',
            'organizational_and_technical_measures_to_ensure_security'=>'string',

        ];
    }
}
