<?php

namespace App\Models;

use Clinically\Companion\Contracts\CompanionSerializable;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model implements CompanionSerializable
{
    /** @use HasFactory<PostFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'body',
        'published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Comment, $this>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @return BelongsToMany<Tag, $this>
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * @param Builder<Post> $query
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('published', true);
    }

    /**
     * @param Builder<Post> $query
     */
    public function scopeDraft(Builder $query): void
    {
        $query->where('published', false);
    }

    /**
     * @return array<string, mixed>
     */
    public function toCompanionArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'published' => $this->published,
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
        ];
    }

    /**
     * @return list<string>
     */
    public function companionRelationships(): array
    {
        return ['comments', 'tags', 'user'];
    }

    /**
     * @return list<string>
     */
    public function companionScopes(): array
    {
        return ['published', 'draft'];
    }
}
