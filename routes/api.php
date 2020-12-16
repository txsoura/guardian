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
    Route::post('login', 'LoginController@login');
    Route::get('{provider}/callback', 'LoginController@handleProviderCallback');
    Route::get('me', 'LoginController@me')->middleware('jwt.auth');
    Route::post('refresh', 'LoginController@refresh')->middleware('jwt.auth');
    Route::post('logout', 'LoginController@logout')->middleware('jwt.auth');

    Route::post('register', 'RegisterController@register');

    Route::post('email/resend', 'VerificationController@resend');
    Route::get('email/verify', 'VerificationController@notice');
    Route::get('email/verify/{id}/{hash}', 'VerificationController@verify');

    Route::post('password/confirm', 'ConfirmPasswordController@confirm');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'ResetPasswordController@reset');
});

Route::group(['prefix' => 'v1'], function () {
    Route::apiResource('users', 'UserController');
    Route::put('users/{user}/approve', 'UserController@approve');
    Route::put('users/{user}/block', 'UserController@block');
    Route::put('users/{user}/role', 'UserController@role');
});
