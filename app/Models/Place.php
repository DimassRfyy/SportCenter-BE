<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
