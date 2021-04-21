<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Social login
Route::group(['prefix' => 'api/v1/auth'], function () {
    Route::get('{provider}', 'Auth\LoginController@redirectToProvider');
    Route::get('{provider}/callback', 'Auth\LoginController@handleProviderCallback');
});

// Route created only to dismiss "Route [password.reset] not defined" error
Route::get('password/reset', function () {
    return [
        'name' => config('app.name'),
        'version' => config('app.version'),
        'locale' => app()->getLocale(),
    ];
})->name('password.reset');
