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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/variables', 'HomeController@variables')->name('variables');

Route::as('realtime.')->group(function () {
    Route::get('temperature/{room}', 'ChartsController@temperature')->name('temp');
    //Route::get('test', 'APIController@test');
});
