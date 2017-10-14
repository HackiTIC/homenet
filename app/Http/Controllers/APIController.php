<?php

namespace App\Http\Controllers;

use Hash;
use App\User;
use App\House;
use Illuminate\Http\Request;
use App\Http\Requests\NewHouse;
//use App\Http\Requests\NewRoom;
use App\Http\Requests\RoomRequest;
use App\Http\Requests\RoomInfo;
use App\Http\Requests\SetRoomTemp;
use App\Http\Requests\SetRoomLight;
use App\Http\Requests\SetRoomPresence;
use App\Http\Requests\CredentialsRequest;
use App\Http\Requests\LinkHouse;

class APIController extends Controller
{
    /**
     * Returns if the login information is OK and sends the user info.
     *
     * @param  CredentialsRequest $request
     * @return array
     */
    public function credentials(CredentialsRequest $request)
    {
        $user = User::where('email', $request->email)->firstOrFail();

        if (!Hash::check($request->password, $user->password)) {
            return [
                'error' => 'Bad credentials',
            ];
        }

        return $user;
    }

    /**
     * Returns the user
     *
     * @return array
     */
    public function user()
    {
        return request()->user()->load('house');
    }

    /**
     * Links the house to a user.
     *
     * @param  LinkHouse $request
     * @return array
     */
    public function linkHouse(LinkHouse $request)
    {
        $house = House::where('auth_token', $request->auth_token)->firstOrFail();

        $house->update(['auth_token' => null]);

        return ['status' => $request->user()->update(['house_id' => $house->id])];
    }

    /**
     * Creates a new house with the given data.
     *
     * @param  NewHouse $request
     * @return array
     */
    public function newHouse(NewHouse $request)
    {
        return House::create([
            'set_temp' => $request->set_temp,
            'room_id' => $request->room_id,
            'api_token' => str_random(60),
            'auth_token' => str_random(10),
        ]);
    }

    /**
     * Creates a new room for the house
     *
     * @param  NewRoom $request
     * @return array
     */
    public function newRoom(Request $request)
    {
        return $request->user()->rooms()->create([
            'name' => str_random(5),
            'temp' => 025.00,
            'light' => false,
            'presence' => false,
            'presence_timeout' => 60,
            'presence_activates_light' => false,
        ]);
    }

    /**
     * Return the user rooms.
     *
     * @param  Request $request
     * @return array
     */
    public function appRooms(Request $request)
    {
        return optional($request->user()->house)->rooms;
    }

    /**
     * Return the house rooms.
     *
     * @param  Request $request
     * @return array
     */
    public function houseRooms(Request $request)
    {
        return optional($request->user())->rooms;
    }

    /**
     * Set the room temperature.
     *
     * @param SetRoomTemp $request
     */
    public function setRoomTemp(SetRoomTemp $request)
    {
        $room = $request->user()->rooms()->where('id', $request->id)->first();

        if (!$room) {
            return ['error' => 'Unauthorized'];
        }

        return ['status' => $room->update(['temp' => $request->temp])];
    }

    /**
     * Set the room light.
     *
     * @param SetRoomLight $request
     */
    public function setRoomLight(SetRoomLight $request)
    {
        $room = $request->user()->rooms()->where('id', $request->id)->first();

        if (!$room) {
            return ['error' => 'Unauthorized'];
        }

        return ['status' => $room->update(['light' => $request->light])];
    }

    /**
     * Set the room presence.
     *
     * @param SetRoomPresence $request
     */
    public function setRoomPresence(SetRoomPresence $request)
    {
        $room = $request->user()->rooms()->where('id', $request->id)->first();

        if (!$room) {
            return ['error' => 'Unauthorized'];
        }

        return ['status' => $room->update(['presence' => $request->presence])];
    }

    /**
     * Return the room information
     *
     * @return array
     */
    public function houseRoom(RoomInfo $request)
    {
        $room = $request->user()->rooms()->where('id', $request->id)->first();

        if (!$room) {
            return ['error' => 'Unauthorized'];
        }

        return $room;
    }

    /**
     * Return the room information.
     *
     * @return array
     */
    public function userRoom(RoomInfo $request)
    {
        $room = $request->user()->house->rooms()->where('id', $request->id)->first();

        if (!$room) {
            return ['error' => 'Unauthorized'];
        }

        return $room;
    }

}
