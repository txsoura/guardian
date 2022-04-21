<?php

use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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

# Version
Route::get('api/v1', function () {
    return [
        'name' => config('app.name'),
        'version' => config('app.version'),
        'locale' => app()->getLocale(),
    ];
});

//Social login
Route::group(['prefix' => 'api/v1/auth'], function () {
// Route created only to dismiss "Driver [login] not supported" error
    Route::get('login', function () {
        throw new MethodNotAllowedHttpException([], 'The GET method is not supported for this route. Supported methods: POST.');
    });

// Route created only to dismiss "Driver  [logout] not supported" error
    Route::post('logout', function () {
        throw new MethodNotAllowedHttpException([], 'The POST method is not supported for this route. Supported methods: GET.');
    });

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
