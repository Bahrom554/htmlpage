<?php

namespace App\UseCases;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\user\UserEditRequest;
use App\Http\Requests\user\UserCreateRequest;


class UserService
{
    public function create(UserCreateRequest $request)
    {
        $user = User::make($request->only('name', 'email'));
        $user->password = bcrypt($request->password);
        $user->save();
        if ($request->filled('role') && Auth::user()->hasRole(User::ROLE_ADMIN)) {
            $user->assignRole($request->role);
        } else {
            $user->assignRole(User::ROLE_USER);
        }
        return $user;
    }

    public function edit(UserEditRequest $request, User $user)
    {
        $user->update($request->only([
            'name',
            'email'
        ]));

        if ($request->filled('role') && Auth::user()->hasRole(User::ROLE_ADMIN)) {
            $user->syncRoles($request->role);
        }

        return $user;
    }
    public function remove(User $user)
    {
        $user->delete();
    }
    public function resetPassword(Request $request, User $user)
    {
        $user->password = bcrypt($request->password);
        $user->save();
        return $user;
    }
}
