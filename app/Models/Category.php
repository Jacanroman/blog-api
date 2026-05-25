<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description'
    ];

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class,'mediable');
    }

    //Get Cover Image for this category
    public function coverImage(): ?Media
    {
        return $this->media()->where('collection','category')->first();
    }
}
