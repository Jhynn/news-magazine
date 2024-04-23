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

    const ARTICLE = 'article';
    const TOPIC = 'topic';
    const USER = 'user';

    const TYPES = [
        self::ARTICLE => Article::class,
        self::TOPIC => Topic::class,
        self::USER => User::class,
    ];

    protected $fillable = [
        'path',
        'mime_type',
        'mediable_id',
        'mediable_type',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'mediable_id' => 'int',
    ];

    protected $appends = [
        'link',
    ];

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    protected function link(): Attribute
    {
        return Attribute::make(
            get: function (){
                $link = null;

                if (isset($this->attributes['path'])) {
                    /** @var Storage $storage */
                    $storage = Storage::disk('public');
                    $link = $storage->url($this->attributes['path']);
                    $link = str_replace('public/', '', $link);
                }

                return $link;
            }
        );
    }

    public static function ownerType(string $type=null): array|string
    {
        if (isset($type) && in_array($type, array_keys(self::TYPES))) return self::TYPES[$type];

        return self::TYPES;
    }
}
