<?php

namespace App\UseCases;

use App\Http\Requests\user\UserCreateRequest;
use App\Http\Requests\user\UserEditRequest;
use App\Models\User;
use Illuminate\Http\Request;


class UserService
{
    public function create(UserCreateRequest $request)
    {
        $user = User::make($request->only('name', 'email'));
         if($request->has('password')){
             $user->password = bcrypt($request->password);
         }
        $user->save();
        return $user;
    }

    public function edit(UserEditRequest $request , User $user){
        $user->update($request->only([
            'name',
            'email',
            'subject',
            'phone'
        ]));
        return $user;

    }
    public function remove(User $user)
    {
        $user->delete();
    }
    public function resetPassword(Request $request , User $user){
        $user->password = bcrypt($request->password);
        $user->save();
        return $user;
    }

   }
