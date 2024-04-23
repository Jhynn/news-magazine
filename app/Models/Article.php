<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    BelongsToMany,
    MorphMany
};
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'content',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class)->withTimestamps();
    }

    public function medias(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}
