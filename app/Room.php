<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'temp', 'light', 'presence', 'presence_timeout', 'presence_activates_light'
    ];

    /**
     * Returns the house of the room
     *
     * @return App\House
     */
    public function house()
    {
        return $this->belongsTo(House::class);
    }
}
