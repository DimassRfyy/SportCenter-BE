<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    public function photos() 
    {
        return $this->hasMany(PlacePhoto::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
