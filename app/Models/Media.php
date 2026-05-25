<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Media extends Model
{
    protected $fillable = [
        'collection',
        'path',
        'filename',
        'disk',
        'mime_type',
        'size',
        'url',
        'alt',
        'type',
    ];

    // The model this media belongs to (Recipe, Category, etc.)
    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    // Returns the public URL to access the file
    public function getPublicUrlAttribute(): string
    {
        if ($this->disk === 'external') {
            return $this->url;
        }

        return asset('storage/' . $this->path);
    }
}