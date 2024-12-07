<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($field) {
            if ($field->thumbnail) {
                Storage::delete($field->thumbnail);
            }
        });

        static::updating(function ($field) {
            if ($field->isDirty('thumbnail')) {
                Storage::delete($field->getOriginal('thumbnail'));
            }
        });
    }
}
