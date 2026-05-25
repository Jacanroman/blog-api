<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Recipe extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'description',
        'ingredients',
        'steps',
        'prep_time',
        'cook_time',
        'servings',
        'difficulty',
        'country',
        'region',
        'category_id',
        'user_id',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'ingredients'  => 'array',
            'steps'        => 'array',
            'published_at' => 'datetime',
        ];
    }

    // ── Relationships ──────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    // ── Media accessors ────────────────────────────────────

    public function featuredImage(): ?Media
    {
        return $this->media()->where('collection', 'featured')->first();
    }

    public function video(): ?Media
    {
        return $this->media()->where('collection', 'video')->first();
    }

    public function gallery()
    {
        return $this->media()->where('collection', 'gallery')->get();
    }

    // ── Scopes ─────────────────────────────────────────────

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeByCountry(Builder $query, string $country): Builder
    {
        return $query->where('country', $country);
    }

    public function scopeByCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByDifficulty(Builder $query, string $difficulty): Builder
    {
        return $query->where('difficulty', $difficulty);
    }
}