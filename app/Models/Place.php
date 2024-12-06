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
        'address',
        'opening_hours',
        'closing_hours',
        'description',
        'city_id',
        'category_id',
    ];
}
