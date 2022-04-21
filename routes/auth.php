<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Auth routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "auth" middleware group. Enjoy building your Auth!
|
*/


Route::group(['prefix' => 'v1/auth'], function () {

    // Login
    Route::post('login', 'Auth\LoginController@login');
    Route::post('login/confirm', 'Auth\LoginController@confirm');
    Route::post('login/resend', 'Auth\LoginController@resend');
    Route::post('login/recovery', 'Auth\LoginController@recovery');

    Route::get('refresh', 'Auth\LoginController@refresh')->middleware(['jwt.auth', 'auth.status']);
    Route::get('logout', 'Auth\LoginController@logout')->middleware(['jwt.auth', 'auth.status']);
    Route::post('register', 'Auth\RegisterController@register');

    // Me
    Route::group(['prefix' => 'me', 'middleware' => ['jwt.auth', 'auth.status']], function () {
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
        Route::post('two-factor/totp/activate', 'TwoFactorController@totpActivate');

        // Access token
        Route::apiResource('tokens', 'AccessTokenController');
    });

    // Email verifications
    Route::post('email/resend', 'Auth\EmailVerificationController@resend');
    Route::post('email/verify', 'Auth\EmailVerificationController@verify')->name('verification.verify');

    // Recover Email
    Route::get('email/recover/{id}/{hash}', 'Auth\EmailRecoveryController@recover')->name('recovery.email');

    // Cellphone verification
    Route::post('cellphone/resend', 'Auth\CellphoneVerificationController@resend');
    Route::post('cellphone/verify', 'Auth\CellphoneVerificationController@verify');

    // Two factor
    Route::post('two-factor/send', 'TwoFactorController@send');
    Route::post('two-factor/verify', 'TwoFactorController@verify');

    // Password reset
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
});
