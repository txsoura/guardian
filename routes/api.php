<?php

use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\UserController;
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

Route::fallback(function () {
    return response()->json(['message' => trans('message.not_found'), 'error' => trans('message.route_not_found')], 404);
});


Route::group(['prefix' => 'v1/auth', 'middleware' => 'api'], function () {
    Route::post('login', 'Auth\LoginController@login');
    Route::get('{provider}/callback', 'Auth\LoginController@handleProviderCallback');
    Route::get('me', 'Auth\LoginController@me')->middleware('jwt.auth');
    Route::post('refresh', 'Auth\LoginController@refresh')->middleware('jwt.auth');
    Route::post('logout', 'Auth\LoginController@logout')->middleware('jwt.auth');

    Route::post('register', 'Auth\RegisterController@register');

    Route::post('email/resend', 'Auth\VerificationController@resend');
    Route::get('email/verify', 'Auth\VerificationController@notice');
    Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify');

    Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
});

Route::group(['prefix' => 'v1', 'middleware' => 'jwt.auth'], function () {
    // ACL
    Route::group(['prefix' => 'acl'], function () {
        Route::apiResource('roles', 'RoleController');
        Route::apiResource('permissions', 'PermissionController');
        Route::apiResource('roles/{role}/permissions', 'RolePermissionController');
        Route::delete('roles/{role}/permissions/{permission}', 'RolePermissionController@destroy')->name('role_permissions.destroy');
    });

    // User
    Route::apiResource('users', 'UserController');
    Route::put('users/{user}/approve', 'UserController@approve')->name('users.approve');
    Route::put('users/{user}/block', 'UserController@block')->name('users.block');
});
