<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {

    Route::group(['middleware' => 'role:' . User::ROLE_ADMIN], function () {
        Route::group(['namespace' => 'admin'], function () {
            Route::get('roles', 'RoleController@index');   //done
            Route::get('roles/{role}', 'RoleController@show'); //done
        });
    });

    Route::group(['middleware' => ['role:' . User::ROLE_ADMIN . '|' . User::ROLE_MANAGER]], function () {

        Route::group(['namespace' => 'admin'], function () {
            Route::apiResource('users', 'UserController'); //done
            Route::apiResource('importance', 'ImportanceController'); //done
            Route::apiResource('comment','CommentController');
            Route::put('users/{user}/change-password', 'UserController@changePassword'); //done
        });
        Route::group(['namespace' => 'user'], function () {
            Route::get('application/{application}/success', 'ApplicationController@success');
            Route::get('application/{application}/reject', 'ApplicationController@reject');
            Route::post('application/{application}/comment', 'ApplicationController@comment');

         
        });
    });

    Route::group(['namespace' => 'user'], function () {

        Route::get('profile', 'ProfileController@show'); //done
        Route::put('profile', 'ProfileController@update'); //done
        Route::put('profile/change-password', 'ProfileController@changePassword'); //done
        Route::get('file', 'FilemanagerController@index'); //done
        Route::delete('file/{id}', 'FilemanagerController@delete'); //done
        Route::get('file/{id}', 'FilemanagerController@show'); //done
        Route::post('file', 'FilemanagerController@uploads'); //done
        Route::apiResource('application', 'ApplicationController'); //done
        Route::get('dash', 'ApplicationController@dash'); //done
        Route::apiResource('device', 'DeviceController'); //done
        Route::apiResource('subject', 'SubjectController'); //done
        Route::apiResource('technique', 'TechniqueController'); //done
        Route::apiResource('staff', 'StaffController'); //done
        Route::apiResource('telecommunication', 'TelecommunicationController'); //done
    });

    
});
