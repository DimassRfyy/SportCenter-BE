<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlacePhoto extends Model
{
    protected $fillable = ['photo', 'place_id'];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}
