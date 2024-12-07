<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Place extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'rating',
        'price',
        'cs_name',
        'cs_avatar',
        'cs_phone',
        'address',
        'opening_hours',
        'closing_hours',
        'description',
        'city_id',
        'category_id',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    public function transactions()
    {
        return $this->hasMany(BookingTransaction::class);
    }

    public function photos() 
    {
        return $this->hasMany(PlacePhoto::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($place) {
            // Delete related fields and their thumbnails
            foreach ($place->fields as $field) {
                if ($field->thumbnail) {
                    Storage::delete($field->thumbnail);
                }
                $field->delete();
            }

            // Delete related photos
            foreach ($place->photos as $photo) {
                if ($photo->photo) {
                    Storage::delete($photo->photo);
                }
                $photo->delete();
            }

            // Delete place thumbnails and cs_avatar
            if ($place->thumbnail) {
                Storage::delete($place->thumbnail);
            }
            if ($place->cs_avatar) {
                Storage::delete($place->cs_avatar);
            }
        });

        static::updating(function ($place) {
            if ($place->isDirty('thumbnail')) {
                Storage::delete($place->getOriginal('thumbnail'));
            }
            if ($place->isDirty('cs_avatar')) {
                Storage::delete($place->getOriginal('cs_avatar'));
            }
        });
    }
}
