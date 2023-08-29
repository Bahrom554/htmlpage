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
            'staff_id'=>'required|integer|exists:staff,id',
            'subject_id'=>'required|integer|exists:subjects,id',
            'purpose_id'=>'required|integer|exists:purposes,id',
            'importance_id'=>'required|integer|exists:importances,id',
            'information_tool'=>'required|array|exists:instruments,id',
            'network_id'=>'required|integer|exists:networks,id',
            'cybersecurity_tool'=>'required|array|exists:instruments,id',
            'threats_to_information_security'=>'required|string',
            'consequences_of_an_incident'=>'required|string',
            'provide_cyber_security'=>'required|string',
        ];
    }
}
