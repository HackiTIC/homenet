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
Route::get('test', function () { return 'OK'; });
Route::post('login', 'APIController@credentials')->name('login');
Route::post('new_house', 'APIController@newHouse')->name('new_house');

Route::middleware('auth:api')->as('app.')->prefix('app')->group(function () {
    Route::post('info', 'APIController@user')->name('user');
    Route::post('link_house', 'APIController@linkHouse')->name('link_house');
    Route::post('rooms', 'APIController@appRooms')->name('rooms');
});
Route::middleware('auth:house')->as('house.')->prefix('house')->group(function () {
    Route::post('rooms', 'APIController@houseRooms')->name('rooms');
    Route::post('new_room', 'APIController@newRoom')->name('new_room');
});
