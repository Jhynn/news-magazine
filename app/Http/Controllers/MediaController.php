<?php

namespace App\Http\Controllers;

use App\Http\Requests\{
    MediaDestroyRequest,
    MediaStoreRequest,
    MediaUpdateRequest
};
use App\Http\Resources\MediaResource;
use App\Models\{
    Article,
    Media,
    Topic,
    User
};
use App\Traits\ApiCommonResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    use ApiCommonResponses;

    const dict = [
        'article' => Article::class,
        'topic' => Topic::class,
        'user' => User::class,
    ];

    protected $storage;

    public function __construct()
    {
        $this->storage = Storage::disk('local');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            return MediaResource::collection(Media::paginate($request->query('per_page')));
        } catch (\Throwable $th) {
            return $this->error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MediaStoreRequest $request)
    {
        try {
            $data = $request->validated();
            $data['mediable_id'] = $data['owner_id'];
            $data['mediable_type'] = self::dict[strtolower($data['owner_type'])];

            unset($data['media']);

            if ($request->hasFile('media')) {
                $path = 'media/' . $request['mediable_id'];

                if (!$this->storage->exists($path))
                    $this->storage->makeDirectory($path);

                // $this->storage->put($path, $request->file('media'));
                $data['path'] = $request->media->store($path, 'local');

                if (!str_contains($data['path'], $path))
                    throw new \Exception(__('something went wrong, sorry'), 500);
            }

            $payload = Media::create($data);

            return MediaResource::make($payload)
                ->additional([
                    'message' => __('the :resource was :action', [
                        'resource' => __('media'),
                        'action' => __('created'),
                    ])
                ]);
        } catch (\Throwable $th) {
            return $this->error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Media $media)
    {
        try {
            return MediaResource::make($media);
        } catch (\Throwable $e) {
            return $this->error($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MediaUpdateRequest $request, Media $media)
    {
        try {
            $data = $request->validated();
            unset($data['media']);

            if ($request->hasFile('media')) {
                $path = 'media/' . $media->mediable_id;

                if ($this->storage->exists($media->path))
                    $this->storage->delete($media->path);

                if (!$this->storage->exists($path))
                    $this->storage->makeDirectory($path);

                $data['path'] = $request->media->store($path, 'local');

                if (!str_contains($data['path'], $path))
                    throw new \Exception(__('something went wrong, sorry'), 500);
            }

            $media->update($data);

            return MediaResource::make($media)
                ->additional([
                    'message' => __('the :resource was :action', [
                        'resource' => __('media'),
                        'action' => __('updated'),
                    ])
                ]);
        } catch (\Throwable $th) {
            return $this->error($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MediaDestroyRequest $request, Media $media)
    {
        try {
            if ($this->storage->exists($media->path))
                $this->storage->delete($media->path);

            $media->delete();

            return MediaResource::make($media)
                ->additional([
                    'message' => __('the :resource was :action', [
                        'resource' => __('media'),
                        'action' => __('deleted'),
                    ])
                ]);
        } catch (\Throwable $th) {
            return $this->error($th);
        }
    }
}
