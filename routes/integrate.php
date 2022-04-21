<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Integrate Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Integrate routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "integrate" middleware group. Enjoy building your Integrate!
|
*/

Route::group(['prefix' => '{app}/{token}'], function () {
    # Version
    Route::get('/', function () {
        return [
            'name' => config('app.name'),
            'version' => config('app.version'),
            'status' => 'OK',
        ];
    });

    // User
    Route::apiResource('users', 'UserController')->only(['index', 'show']);
});
