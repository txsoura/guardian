<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => '{app}/{token}'], function () {
    # Version
    Route::get('/', function () {
        return [
            'name' => config('app.name'),
            'version' => config('app.version'),
            'locale' => app()->getLocale(),
        ];
    });

    Route::fallback(function () {
        return response()->json(['message' => trans('message.not_found'), 'error' => trans('message.route_not_found')], 404);
    });

    // ACL
    Route::group(['prefix' => 'acl'], function () {
        Route::apiResource('roles', 'RoleController');
        Route::apiResource('permissions', 'PermissionController');
        Route::get('roles/{role}/permissions', 'RolePermissionController@index');
        Route::post('roles/{role}/permissions', 'RolePermissionController@store');
        Route::delete('roles/{role}/permissions/{permission}', 'RolePermissionController@destroy');
    });

    // User
    Route::apiResource('users', 'UserController');
    Route::put('users/{user}/approve', 'UserController@approve');
    Route::put('users/{user}/block', 'UserController@block');

    // Access token
    Route::apiResource('users/{user}/tokens', 'AccessTokenController');
});
