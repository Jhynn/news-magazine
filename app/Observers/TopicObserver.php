<?php

namespace App\Observers;

use App\Models\Topic;
use Exception;
use Illuminate\Support\Str;

class TopicObserver
{
    public function creating(Topic $topic)
    {
        $payload = Str::slug($topic->title);
        $aux = Topic::where('name', '=', $payload)->firstOr(function() {
            return false;
        });

        if ($aux)
            throw new Exception(__('this :resource already exists', ['resource' => __('topic')]), 400);

        $topic->name = $payload;
    }

    /**
     * Handle the Topic "created" event.
     */
    public function created(Topic $topic): void
    {
        // 
    }

    public function updating(Topic $topic)
    {
        $payload = Str::slug($topic->title);
        $aux = Topic::where('name', '=', $payload)->where('id', '<>', $topic->id)->firstOr(function() {
            return false;
        });

        if ($aux)
            throw new Exception(__('this :resource already exists', ['resource' => __('topic')]), 400);

        $topic->name = $payload;
    }

    /**
     * Handle the Topic "updated" event.
     */
    public function updated(Topic $topic): void
    {
        //
    }

    /**
     * Handle the Topic "deleted" event.
     */
    public function deleted(Topic $topic): void
    {
        //
    }

    /**
     * Handle the Topic "restored" event.
     */
    public function restored(Topic $topic): void
    {
        //
    }

    /**
     * Handle the Topic "force deleted" event.
     */
    public function forceDeleted(Topic $topic): void
    {
        //
    }
}
