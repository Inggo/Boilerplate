<?php

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

$pages = [
    'users',
    'users/create',
    'users/[0-9]+',
    'users/[0-9]+/edit',
    'users/[0-9]+/change-password',
    'activity',
    'change-password',
    'change-password/[0-9]+',
    'user/activity'
];

Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout');

Route::get('/{page?}', function () {
    return view('index', ['user' => Auth::user()]);
})->where('page', '(' . implode($pages, '|') . ')');
