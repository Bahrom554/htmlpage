<?php

namespace App\Http\Requests\subject;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Subject;

class SubjectEditRequest extends FormRequest
{

    public function rules()
    {
        return [
            'name' => 'string|unique:subjects,name,'.$this->subject->id,
            'address_legal'=>'string',
            'address_fact'=>'nullable|string',
            'subject_type_id' => 'integer|exists:subject_types,id',
            'email'=>'nullable|email|max:255',
            'phone'=>'string'
        ];
    }
}
