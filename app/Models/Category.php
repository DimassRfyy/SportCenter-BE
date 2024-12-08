<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
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

        static::deleting(function ($category) {
            if ($category->icon) {
                Storage::delete($category->icon);
            }
            if ($category->thumbnail) {
                Storage::delete($category->thumbnail);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('icon')) {
                Storage::delete($category->getOriginal('icon'));
            }
            if ($category->isDirty('thumbnail')) {
                Storage::delete($category->getOriginal('thumbnail'));
            }
        });
    }

    public function getThumbnailUrlAttribute()
    {
        return Storage::disk('public')->url($this->thumbnail);
    }
}
