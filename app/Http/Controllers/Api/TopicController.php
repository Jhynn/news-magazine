<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\{
    TopicShowRequest,
    TopicStoreRequest,
    TopicUpdateRequest
};
use App\Http\Requests\TopicDestroyRequest;
use App\Http\Resources\TopicResource;
use App\Models\Topic;
use App\Services\TopicService;
use App\Traits\ApiCommonResponses;
use Throwable;

class TopicController extends Controller
{
    use ApiCommonResponses;

    public function __construct(private TopicService $service) { }

    /**
     * Display a listing of the resource.
     */
    public function index(TopicShowRequest $request)
    {
        try {
            $payload = $this->service->index($request);

            return TopicResource::collection($payload)->response();
        } catch (Throwable $th) {
            return $this->error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TopicStoreRequest $request)
    {
        try {
            $payload = $this->service->store($request->validated());

            return TopicResource::make($payload)
                ->additional([
                    'message' => __(
                        'the :resource was :action', ['resource' => __('topic'), 'action' => __('created')]
                    )
                ])->response();
        } catch (Throwable $th) {
            return $this->error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TopicShowRequest $request, Topic $topic)
    {
        try {
            $payload = $this->service->show($topic);

            return TopicResource::make($payload)->response();
        } catch (Throwable $th) {
            return $this->error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TopicUpdateRequest $request, Topic $topic)
    {
        try {
            $payload = $this->service->update($request->validated(), $topic);

            return TopicResource::make($payload)
                ->additional([
                    'message' => __(
                        'the :resource was :action', ['resource' => __('topic'), 'action' => __('updated')]
                    )
                ])->response();
        } catch (Throwable $th) {
            return $this->error($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TopicDestroyRequest $request, Topic $topic)
    {
        try {
            $payload = $this->service->destroy($topic);

            return TopicResource::make($payload)
                ->additional([
                    'message' => __(
                        'the :resource was :action', ['resource' => __('topic'), 'action' => __('deleted')]
                    )
                ])->response();
        } catch (Throwable $th) {
            return $this->error($th);
        }
    }
}
