<?php

namespace App\Http\Requests\user;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class UserEditRequest extends FormRequest
{

    public function rules()
    {
        return [
            'name'=>'string|max:255',
            'email' => 'email|unique:users,email,'.$this->user,
            'subject_id'=>'integer|exists:subjects,id',
            'phone'=>'string'
        ];
    }
}
