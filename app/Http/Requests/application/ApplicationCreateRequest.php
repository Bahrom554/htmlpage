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
            'definition'=>'nullable|string',
            'certificates'=>'nullable|array',
            'licenses'=>'nullable|array',
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
