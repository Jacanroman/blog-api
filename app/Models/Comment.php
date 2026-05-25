<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    protected $fillable = [
        'recipe_id',
        'user_id',
        'parent_id',
        'guest_name',
        'guest_email',
        'body',
        'status',
    ];

    // ── Relationships ──────────────────────────────────────

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Parent comment this is a reply to
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // Replies to this comment
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    // ── Helpers ────────────────────────────────────────────

    // Returns the display name — registered user or guest
    public function authorName(): string
    {
        return $this->user?->name ?? $this->guest_name ?? 'Anonymous';
    }
}