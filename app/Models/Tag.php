<?php

namespace App\Models;

use Clinically\Companion\Contracts\CompanionSerializable;
use Database\Factories\TagFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model implements CompanionSerializable
{
    /** @use HasFactory<TagFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * @return BelongsToMany<Post, $this>
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    /**
     * @return array<string, mixed>
     */
    public function toCompanionArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }

    /**
     * @return list<string>
     */
    public function companionRelationships(): array
    {
        return ['posts'];
    }

    /**
     * @return list<string>
     */
    public function companionScopes(): array
    {
        return [];
    }
}
