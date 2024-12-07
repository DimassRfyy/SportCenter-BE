<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = [
        'name',
        'price',
        'thumbnail',
        'is_available',
        'is_indoor',
        'floor_type',
        'place_id',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function transactions()
    {
        return $this->hasMany(BookingTransaction::class);
    }
}
