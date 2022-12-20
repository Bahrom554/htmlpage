<?php

namespace App\Http\Requests\application;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationEditRequest extends FormRequest
{

    public function rules()
    {
        return [
            'name'=>'string',
            'definition'=>'nullable|string',
            'certificate'=>'nullable|integer|exists:files,id',
            'license'=>'nullable|integer|exists:files,id',
            'device_id'=>'nullable|integer|exists:devices,id',
            'error_or_broken'=>'nullable|string',
            'telecommunication_network'=>'nullable|string',
            'provide_cyber_security'=>'nullable|string',
            'threats_to_information_security'=>'nullable|string',
            'consequences_of_an_incident'=>'nullable|string',
            'organizational_and_technical_measures_to_ensure_security'=>'nullable|string'
        ];
    }
}
