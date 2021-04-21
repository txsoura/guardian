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


Route::group(['prefix' => 'v1/auth'], function () {

    // Login
    Route::post('login', 'Auth\LoginController@login');
    Route::post('login/confirm', 'Auth\LoginController@confirm');
    Route::post('login/resend', 'Auth\LoginController@resend');
    Route::post('login/recovery', 'Auth\LoginController@recovery');

    Route::post('refresh', 'Auth\LoginController@refresh')->middleware('jwt.auth');
    Route::post('logout', 'Auth\LoginController@logout')->middleware('jwt.auth');
    Route::post('register', 'Auth\RegisterController@register');

    // Me
    Route::group(['prefix' => 'me', 'middleware' => 'jwt.auth'], function () {
        Route::get('/', 'MeController@show');
        Route::put('/', 'MeController@update');
        Route::delete('/', 'MeController@destroy');
        Route::post('avatar', 'MeController@uploadAvatar');
        Route::put('email', 'MeController@updateEmail');
        Route::put('cellphone', 'MeController@updateCellphone');
        Route::put('password', 'MeController@updatePassword');
        Route::get('permissions', 'MeController@permissions');
        Route::get('two-factor', 'TwoFactorController@show');
        Route::put('two-factor', 'TwoFactorController@update');
    });

    // Email verifications
    Route::post('email/resend', 'Auth\EmailVerificationController@resend');
    Route::get('email/verify/{id}/{hash}', 'Auth\EmailVerificationController@verify')->name('verification.verify');

    // Cellphone verification
    Route::post('cellphone/resend', 'Auth\CellphoneVerificationController@resend');
    Route::post('cellphone/verify', 'Auth\CellphoneVerificationController@verify');

    // Two factor
    Route::get('two-factor/send', 'TwoFactorController@send');
    Route::post('two-factor/verify', 'TwoFactorController@verify');

    // Password reset
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    // Access token
    Route::apiResource('me/tokens', 'AccessTokenController');
});
