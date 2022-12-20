<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;

class RoleController extends Controller
{
    public function index()
    {
       $roles = Role::whereNotIn('name', ['admin'])->get();
       return $roles;
    }
   
    public function show(Request $request, $id){
   
          $query=QueryBuilder::for(Role::class);
        $query->where('id',$id)->whereNotIn('name', ['admin']);
        $query->allowedIncludes(!empty($request->include) ? explode(',', $request->get('include')) : []);
          return $query->get();
    }
}
