<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Access Token Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Access Token routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "access_token" middleware group. Enjoy building your Access Token!
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



Route::group(['prefix' => 'v1/auth'], function () {
    Route::get('me', 'MeController@show');
});
