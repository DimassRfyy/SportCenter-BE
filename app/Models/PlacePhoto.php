<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PlacePhoto extends Model
{
    protected $fillable = ['photo', 'place_id'];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($placePhoto) {
            if ($placePhoto->photo) {
                Storage::delete($placePhoto->photo);
            }
        });

        static::updating(function ($placePhoto) {
            if ($placePhoto->isDirty('photo')) {
                Storage::delete($placePhoto->getOriginal('photo'));
            }
        });
    }
}
