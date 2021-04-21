<?php

use Illuminate\Support\Facades\Auth;
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

# Version
Route::get('/v1', function () {
    return [
        'name' => config('app.name'),
        'version' => config('app.version'),
        'locale' => app()->getLocale(),
    ];
});

Route::group(['prefix' => 'v1', 'middleware' => 'jwt.auth'], function () {
    // ACL
    Route::group(['prefix' => 'acl'], function () {
        Route::apiResource('roles', 'RoleController');
        Route::apiResource('permissions', 'PermissionController');
        Route::get('roles/{role}/permissions', 'RolePermissionController@index')->name('roles.permissions.index');
        Route::post('roles/{role}/permissions', 'RolePermissionController@store')->name('roles.permissions.store');
        Route::delete('roles/{role}/permissions/{permission}', 'RolePermissionController@destroy')->name('roles.permissions.destroy');
    });

    // User
    Route::apiResource('users', 'UserController');
    Route::put('users/{user}/approve', 'UserController@approve')->name('users.approve');
    Route::put('users/{user}/block', 'UserController@block')->name('users.block');
});
