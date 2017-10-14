<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChartsController extends Controller
{
    /**
     * Returns the temperature of the room
     *
     * @param  Room   $room
     * @return Response
     */
    public function temperature(Room $room)
    {
        return ['value' => $room->temp];
    }
}
