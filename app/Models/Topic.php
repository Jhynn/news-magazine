<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    BelongsToMany,
    MorphMany
};

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class)->withTimestamps();
    }

    public function medias(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}
