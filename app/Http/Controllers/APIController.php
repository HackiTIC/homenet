<?php

namespace App\Http\Controllers;

use Hash;
use App\User;
use App\House;
use Illuminate\Http\Request;
//use App\Http\Requests\NewHouse;
//use App\Http\Requests\NewRoom;
use App\Http\Requests\RoomRequest;
use App\Http\Requests\RoomInfo;
use App\Http\Requests\SetHomeSettings;
use App\Http\Requests\SetRoomTemp;
use App\Http\Requests\SetRoomLight;
use App\Http\Requests\SetRoomPresence;
use App\Http\Requests\SetRoomName;
use App\Http\Requests\SetPresenceTimeout;
use App\Http\Requests\SetPresenceActivatesLight;
use App\Http\Requests\CredentialsRequest;
use App\Http\Requests\LinkHouse;
use App\Http\Requests\SetLightThreshold;
use Telegram\Bot\Api;

class APIController extends Controller
{
    public $telegram;
    public $channel;

    public function __construct()
    {
        $this->telegram = new Api(config('telegram.bot_token'));
        $this->channel = '-245915591';
    }

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

        $this->telegram->sendMessage([
            'chat_id' => $this->channel,
            'text' => "User #{$user->id} just logged in",
        ]);

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

        $this->telegram->sendMessage([
            'chat_id' => $this->channel,
            'text' => "linked house #{$house->id} to user #{$request->user()->id}",
        ]);

        return ['status' => $request->user()->update(['house_id' => $house->id])];
    }

    /**
     * Creates a new house with the given data.
     *
     * @param  NewHouse $request
     * @return array
     */
    public function newHouse(Request $request)
    {
        $this->telegram->sendMessage([
            'chat_id' => $this->channel,
            'text' => "Created a new house",
        ]);

        return House::create([
            'set_temp' => 25.00,
            'room_id' => null,
            'api_token' => str_random(60),
            'auth_token' => str_random(10),
            'hysteresis_threshold' => 5,
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
        $name = str_random(5);

        $this->telegram->sendMessage([
            'chat_id' => $this->channel,
            'text' => "Created a new room <{$name}>",
        ]);

        return $request->user()->rooms()->create([
            'name' => $name,
            'temp' => 025.00,
            'light' => false,
            'presence' => false,
            'presence_timeout' => 60,
            'presence_activates_light' => false,
            'light_threshold' => 100,
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
        return $request->user()->rooms->map->only([
            'id', 'house_id', 'light', 'presence', 'presence_activates_light', 'presence_timeout', 'temp'
        ]);
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

        $this->telegram->sendMessage([
            'chat_id' => $this->channel,
            'text' => "Updated room #{$room->id} temperature from <{$room->temp}> to <{$request->temp}>",
        ]);

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

        $this->telegram->sendMessage([
            'chat_id' => $this->channel,
            'text' => "Updated room #{$room->id} light from <{$room->light}> to <{$request->light}>",
        ]);

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

        $this->telegram->sendMessage([
            'chat_id' => $this->channel,
            'text' => "Updated room #{$room->id} presence from <{$room->presence}> to <{$request->presence}>",
        ]);

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

    /**
     * Set the room name.
     *
     * @param SetRoomName $request
     */
    public function setRoomName(SetRoomName $request)
    {
        $room = $request->user()->house->rooms()->where('id', $request->id)->first();

        if (!$room) {
            return ['error' => 'Unauthorized'];
        }

        $this->telegram->sendMessage([
            'chat_id' => $this->channel,
            'text' => 'Room name changed from <' . $room->name . '> to <' . $request->name . '>',
        ]);

        return ['status' => $room->update(['name' => $request->name])];
    }

    /**
     * Set the room temp.
     *
     * @param SetRoomTemp $request
     */
    public function setHomeSettings(SetHomeSettings $request)
    {
        $this->telegram->sendMessage([
            'chat_id' => $this->channel,
            'text' => 'Updated home #' . $request->user()->house->id . ' settings',
        ]);

        return [
            'status' => $request->user()->house()->update(
                $request->only([
                    'set_temp', 'room_id', 'hysteresis_threshold'
                ])
            )
        ];
    }

    /**
     * Set the room light.
     *
     * @param SetRoomLight $request
     */
    public function setAppRoomLight(SetRoomLight $request)
    {
        $room = $request->user()->house->rooms()->where('id', $request->id)->first();

        if (!$room) {
            return ['error' => 'Unauthorized'];
        }

        $this->telegram->sendMessage([
            'chat_id' => $this->channel,
            'text' => "Updated room #{$room->id} light from <{$room->light}> to <{$request->light}>",
        ]);

        return ['status' => $room->update(['light' => $request->light])];
    }

    /**
     * Set the presence timeout of the room.
     *
     * @param SetPresenceTimeout $request
     */
    public function setPresenceTimeout(SetPresenceTimeout $request)
    {
        $room = $request->user()->house->rooms()->where('id', $request->id)->first();

        if (!$room) {
            return ['error' => 'Unauthorized'];
        }

        $this->telegram->sendMessage([
            'chat_id' => $this->channel,
            'text' => "Updated room #{$room->id} presence timeout from <{$room->presence_timeout}> to <{$request->presence_timeout}>",
        ]);

        return ['status' => $room->update(['presence_timeout' => $request->presence_timeout])];
    }

    /**
     * Set the presence activates light of the room.
     *
     * @param SetPresenceActivatesLight $request
     */
    public function setPresenceActivatesLight(SetPresenceActivatesLight $request)
    {
        $room = $request->user()->house->rooms()->where('id', $request->id)->first();

        if (!$room) {
            return ['error' => 'Unauthorized'];
        }

        $this->telegram->sendMessage([
            'chat_id' => $this->channel,
            'text' => "Updated room #{$room->id} presence activates light from <{$room->presence_activates_light}> to <{$request->presence_activates_light}>",
        ]);

        return ['status' => $room->update(['presence_activates_light' => $request->presence_activates_light])];
    }

    /**
     * Set the light threshold.
     *
     * @param SetLightThreshold $request
     */
    public function setLightThreshold(SetLightThreshold $request)
    {

        $room = $request->user()->house->rooms()->where('id', $request->id)->first();

        if (!$room) {
            return ['error' => 'Unauthorized'];
        }

        $this->telegram->sendMessage([
            'chat_id' => $this->channel,
            'text' => "Updated room #{$room->id} light threshold from <{$room->light_threshold}> to <{$request->light_threshold}>",
        ]);

        return ['status' => $room->update(['light_threshold' => $request->light_threshold])];
    }

    /**
     * Return the information of the house.
     *
     * @param  Request $request
     * @return array
     */
    public function houseInfo(Request $request)
    {
        return $request->user();
    }

}
