<?php

use Illuminate\Http\Request;

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

//protected routes
Route::middleware('auth:api', 'throttle:60,1')->group(function () {
    Route::namespace('Admin')->group(function () {
        Route::resource('users', 'UserController');

        Route::post('check-email', 'UserController@checkEmail');
        Route::post('check-username', 'UserController@checkUsername');

        Route::get('activity', 'ActivityController@index');
    });

    Route::get('change-password/{id?}', 'ChangePasswordController@edit');
    Route::patch('change-password', 'ChangePasswordController@update');
});
