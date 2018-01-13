-<?php

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

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/home', 'DashboardController@index')->name('home');
    route::get('/fitbit/store', 'FitbitController@store')->name('fitbitHook');
    route::get('/fitbit/setup', 'FitbitController@setup')->name('fitbitSetup');

    //sync
    route::get('/sync/weight', 'SyncController@weightSync');
});
