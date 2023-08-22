<?php

namespace App\Http\Requests\application;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationEditRequest extends FormRequest
{

    public function rules()
    {
        return [
            'name'=>'string',
            'staffs'=>'required|array|exists:staff,id',
            'purpose_id'=>'integer||exists:purposes,id',
            'error_or_broken'=>'string',
            'devices'=>'nullable|array|exists:devices,id',
            'techniques'=>'nullable|array|exists:techniques,id',
            'licenses'=>'nullable|array|exists:files,id',
            'certificates'=>'nullable|array|exists:files,id',
            'telecommunications'=>'array|exists:telecommunications,id',
            'provide_cyber_security'=>'string',
            'threats_to_information_security'=>'string',
            'consequences_of_an_incident'=>'string',
            'organizational_and_technical_measures_to_ensure_security'=>'string',
            'subject_id'=>'integer|exists:subjects,id',
            'documents'=>'nullable|array|exists:files,id',
            'importance_id'=>'integer|exists:importances,id'
           ];

    }
}
