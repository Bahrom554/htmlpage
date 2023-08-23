<?php

namespace App\Http\Requests\subject;

use Illuminate\Foundation\Http\FormRequest;

class SubjectCreateRequest extends FormRequest
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
            'name'=>'required|string|unique:subjects',
           'address_legal'=>'required|string',
           'address_fact'=>'nullable|string',
           'subject_type_id'=>'required|integer|exists:subject_types,id',
           'email'=>'nullable|email|max:255',
           'phone'=>'required|string'
        ];
    }
}
