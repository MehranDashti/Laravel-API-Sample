<?php

use Illuminate\Http\Request;
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

Route::group(['middleware' => ['cors', 'json.response']], static function () {
    Route::post('/login', 'Auth\ApiAuthController@login')->name('login.api');
    Route::post('/register', 'Auth\ApiAuthController@register')->name('register.api');

    Route::middleware('auth:api')->group(static function () {
        Route::post('/logout', 'Auth\ApiAuthController@logout')->name('logout.api');
        Route::get('customer', 'Api\v1\CustomerController@index')->name('index.customer');

        Route::middleware('api.admin')->group(function () {
            Route::post('customer/import', 'Api\v1\CustomerController@import')->name('import.customer');
        });
    });
});

