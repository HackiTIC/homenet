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


Route::get('routes', function() {
    $routeCollection = Route::getRoutes();

    echo "<table style='width:100%'>";
    echo "<tr>";
        echo "<td width='10%'><h4>Route name</h4></td>";
        echo "<td width='10%'><h4>HTTP method</h4></td>";
        echo "<td width='80%'><h4>API endpoint</h4></td>";
    echo "</tr>";
    //dd($routeCollection->getRoutesByName());
    foreach ($routeCollection->getRoutesByName() as $key => $value) {
        echo "<tr>";
            echo "<td>" . $key . "</td>";
            echo "<td>" . $value->methods[0] . "</td>";
            echo "<td>" . $value->uri . "</td>";
        echo "</tr>";
    }
    echo "</table>";
});
Route::as('app.')->prefix('app')->group(function () {
    Route::post('login', 'APIController@credentials')->name('login');
    Route::middleware('auth:api')->group(function () {
        Route::post('info', 'APIController@user')->name('user');
        Route::post('link_house', 'APIController@linkHouse')->name('link_house');
        Route::post('rooms', 'APIController@appRooms')->name('rooms');
        Route::post('room', 'APIController@userRoom')->name('room');
        Route::post('set_room_name', 'APIController@setRoomName')->name('set_name');
        Route::post('set_room_temp', 'APIController@setAppRoomTemp')->name('set_temp');
        Route::post('set_room_light', 'APIController@setAppRoomLight')->name('set_light');
    });
});
Route::as('house.')->prefix('house')->group(function () {
    Route::post('new_house', 'APIController@newHouse')->name('new_house');
    Route::middleware('auth:house')->group(function () {
        Route::post('rooms', 'APIController@houseRooms')->name('rooms');
        Route::post('new_room', 'APIController@newRoom')->name('new_room');
        Route::post('room', 'APIController@houseRoom')->name('room');
        Route::post('set_room_temp', 'APIController@setRoomTemp')->name('set_temp');
        Route::post('set_room_light', 'APIController@setRoomLight')->name('set_light');
        Route::post('set_room_presence', 'APIController@setRoomPresence')->name('set_presence');
    });
});
