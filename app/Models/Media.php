<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'mime_type',
        'mediable_id',
        'mediable_type',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'attachmentable_id' => 'string',
    ];

    protected $appends = [
        'link'
    ];

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    protected function link(): Attribute
    {
        return Attribute::make(
            get: fn () => (isset($this->attributes['path']) ? Storage::disk('public')->url($this->attributes['path']) : null)
        );
    }
}
