<?php

namespace App\Http\Requests\application;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationEditRequest extends FormRequest
{

    public function rules()
    {
        return [
            'name'=>'string',
            'staff_id'=>'integer|exists:staff,id',
            'subject_id'=>'integer|exists:subjects,id',
            'purpose_id'=>'integer|exists:purposes,id',
            'importance_id'=>'integer|exists:importances,id',
            'information_tool'=>'array|exists:instruments,id',
            'network_id'=>'integer|exists:networks,id',
            'cybersecurity_tool'=>'array|exists:instruments,id',
            'threats_to_information_security'=>'string',
            'consequences_of_an_incident'=>'string',
            'provide_cyber_security'=>'string',
           ];

    }
}
