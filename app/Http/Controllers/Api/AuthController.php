<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TokenResource;
use App\Services\AuthService;
use App\Traits\ApiCommonResponses;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiCommonResponses;

    public function __construct(private AuthService $service) { }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return $this->success($this->service->index());
        } catch (\Exception $e) {
            return $this->error($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->service->store($request->validated());
        } catch (\Exception $e) {
            return $this->error($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $token = $this->service->destroy($request);

            return TokenResource::make($token)->additional(['message' => __('token_revoked')]);
        } catch (\Exception $e) {
            return $this->error($e);
        }
    }

    public function revokeAllMyTokens()
    {
        try {
            auth()->user()->tokens()->delete();

            return $this->success([], ['message' => __('all_tokens_revoked')]);
        } catch (\Exception $e) {
            return $this->error($e);
        }
    }
}
