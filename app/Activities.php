<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activities extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'user_id', 'distance', 'date', 'strava_activity_id'
    ];
}