<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\{
    ArticleDestroyRequest,
    ArticleShowRequest,
    ArticleStoreRequest,
    ArticleUpdateRequest
};
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Services\ArticleService;
use App\Traits\ApiCommonResponses;

class ArticleController extends Controller
{
    use ApiCommonResponses;

    public function __construct(private ArticleService $service) { }

    /**
     * Display a listing of the resource.
     */
    public function index(ArticleShowRequest $request)
    {
        try {
            return ArticleResource::collection($this->service->index($request));
        } catch (\Throwable $th) {
            $this->error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleStoreRequest $request)
    {
        try {
            return ArticleResource::make($this->service->store($request->validated()));
        } catch (\Throwable $th) {
            $this->error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ArticleShowRequest $request, Article $article)
    {
        try {
            return ArticleResource::make($this->service->show($article));
        } catch (\Throwable $th) {
            $this->error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleUpdateRequest $request, Article $article)
    {
        try {
            return ArticleResource::make($this->service->update($request->validated(), $article));
        } catch (\Throwable $th) {
            $this->error($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ArticleDestroyRequest $request, Article $article)
    {
        try {
            return ArticleResource::make($this->service->destroy($article));
        } catch (\Throwable $th) {
            $this->error($th);
        }
    }
}
