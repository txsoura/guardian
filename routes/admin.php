<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "admin" middleware group. Enjoy building your Admin!
|
*/

Route::group(['middleware' => ['jwt.auth', 'verified']], function () {
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
