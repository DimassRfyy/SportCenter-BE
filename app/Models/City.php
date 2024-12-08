<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class City extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'thumbnail'];

    public function places()
    {
        return $this->hasMany(Place::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($city) {
            if ($city->icon) {
                Storage::delete($city->icon);
            }
            if ($city->thumbnail) {
                Storage::delete($city->thumbnail);
            }
        });

        static::updating(function ($city) {
            if ($city->isDirty('icon')) {
                Storage::delete($city->getOriginal('icon'));
            }
            if ($city->isDirty('thumbnail')) {
                Storage::delete($city->getOriginal('thumbnail'));
            }
        });
    }

    public function getIconUrlAttribute()
    {
        return Storage::disk('public')->url($this->icon);
    }
}
