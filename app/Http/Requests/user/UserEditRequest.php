<?php

namespace App\Http\Requests\user;

use Illuminate\Foundation\Http\FormRequest;

class UserEditRequest extends FormRequest
{

    public function rules()
    {
        return [
            'name'=>'string|max:255',
            'username'=>'string|max:255|unique:users',
            'subject_id'=>'integer|exists:subjects,id',
            'phone'=>'string'
        ];
    }
}
