<?php

namespace App\Http\Requests\user;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
            'name'=>'required|string|max:255',
            'email'=>'required|email|max:255|unique:users',
            'password'=>'required|confirmed|string|min:6',
            'subject_id'=>'required|integer|exists:subjects,id'
        ];
    }
}
