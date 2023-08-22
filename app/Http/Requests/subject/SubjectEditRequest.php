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
            'address_legal'=>'nullable|string',
            'address_fact'=>'nullable|string',
            'subject_type_id' => 'nullable|integer|exists:subject_types,id',
            'documents' => 'nullable|array|exists:files,id'
        ];
    }
}
