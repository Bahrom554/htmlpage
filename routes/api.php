<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware'=>['auth:api','role:'.User::ROLE_ADMIN.'|'.User::ROLE_MANAGER.'|'.User::ROLE_USER]],function () {

    Route::group(['middleware'=>'role:admin','namespace'=>'admin'],function (){
        Route::apiResource('users','UserController');
        Route::get('roles','RoleController@index');
        Route::get('roles/{role}', 'RoleController@show');
        Route::post('users/{user}/roles',  'UserController@assignRole');
        Route::delete('/users/{user}/roles/{role}', 'UserController@removeRole');
        Route::put('users/{user}/change-password','UserController@changePassword');
        Route::apiResource('importance','ImportanceController');
    });
    Route::group(['namespace'=>'user'],function (){
        Route::get('profile','ProfileController@show');
        Route::put('profile','ProfileController@update');
        Route::put('profile/change-password','ProfileController@changePassword');
        Route::get('file', 'FilemanagerController@index');
        Route::delete('file/{id}', 'FilemanagerController@delete');
        Route::post('file', 'FilemanagerController@uploads');
        Route::apiResource('application','ApplicationController');
        Route::get('dash','ApplicationController@dash');
        Route::apiResource('device','DeviceController');
        Route::apiResource('technique','TechniqueController');
        Route::apiResource('staff','StaffController');
        Route::apiResource('telecommunication','TelecommunicationController');
        Route::put('application/{application}/rester','ApplicationController@rester');
    });

});

Route::group(['middleware'=>['auth:api','role:'.User::ROLE_ADMIN]],function () {
    Route::group(['namespace'=>'user'],function (){
      Route::put('application/{application}/success','ApplicationController@success');
      Route::put('application/{application}/importance','ApplicationController@importance');

    });

});
Route::group(['middleware'=>['auth:api','role:'.User::ROLE_ADMIN.'|'.User::ROLE_MANAGER]],function () {
    Route::group(['namespace'=>'user'],function (){
      Route::put('application/{application}/reject','ApplicationController@reject');
      Route::put('application/{application}/register','ApplicationController@register');
    });

});
