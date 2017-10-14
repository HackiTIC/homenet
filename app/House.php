<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'set_temp', 'room_id', 'api_token', 'auth_token'
    ];
    /**
     * Return the house users.
     *
     * @return Collection
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Returns the rooms of the house.
     *
     * @return Collection
     */
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Return the reference room for the temperature.
     *
     * @return App\Room
     */
    public function referenceRoom()
    {
        return $this->belongsTo(Room::class);
    }
}
