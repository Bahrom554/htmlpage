<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\UseCases\UserService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\UserListResource;
use App\Http\Requests\user\UserEditRequest;
use App\Http\Requests\user\UserCreateRequest;

class UserController extends Controller
{
    private $service;
    public function __construct(UserService $service)
    {

        $this->service = $service;
    }
    public function index(Request $request)
    {
        $query = QueryBuilder::for(User::class);
        if (!empty(request()->get('search'))) {
            $query->where('name', 'like', '%' . request()->get('search') . '%')
                ->orWhere('email', 'like', '%' . request()->get('search') . '%');
        }
        $query->where('id', '<>', Auth::id());
        $query->allowedIncludes(!empty($request->include) ? explode(',', $request->get('include')) : []);
        $query->allowedSorts(request()->sort);
        if (Gate::denies('admin')) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', User::ROLE_USER);
            });
        }
        return $query->paginate(10);
    }

    public function store(UserCreateRequest $request)
    {
        $user = $this->service->create($request);
        return new UserListResource($user);
    }

    public function show($id)
    {
        $user = $this->FilterByRole($id);

        return new UserListResource($user);
    }


    public function update(UserEditRequest $request, $id)
    {
      
        $user = $this->FilterByRole($id);

        $this->service->edit($request, $user);
        return new UserListResource(User::findOrFail($user->id));
    }


    public function destroy($id)
    {
        $user = $this->FilterByRole($id);

        if (!$user->hasRole(User::ROLE_ADMIN)) {
            $this->service->remove($user);
        }
        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function changePassword(Request $request, $id)
    {
        $user = $this->FilterByRole($id);
        $this->validate($request, [
            'password' => 'required|confirmed|string|min:6',
        ]);
        $this->service->resetPassword($request, $user);
        DB::table('oauth_access_tokens')
            ->where('user_id', $user->id)->update([
                'revoked' => true
            ]);
        return response()->json([], Response::HTTP_RESET_CONTENT);
    }


    protected function FilterByRole($id)
    {
        $user = User::where('id', $id);
        if (Gate::denies('admin')) {
            $user = $user->whereHas('roles', function ($q) {
                $q->where('name', User::ROLE_USER);
            });
        }
        return $user->firstOrFail();
    }
































    // public function assignRole(Request $request, User $user)
    // {
    //     if ($user->hasAnyRole($request->role)) {
    //         return response()->json([], Response::HTTP_NOT_MODIFIED);
    //     }
    //     $user->syncRoles($request->role);
    //     if($user->hasRole(User::ROLE_ADMIN)){
    //         $user->syncRoles(User::ROLE_ADMIN);
    //     }
    //     return $user;
    // }

    // public function removeRole(User $user, Role $role)
    // {
    //     if ($user->hasRole($role) ) {
    //         $user->removeRole($role);
    //         return response()->json([], Response::HTTP_NO_CONTENT);
    //     }

    //     return response()->json([], Response::HTTP_NOT_MODIFIED);
    // }

}
