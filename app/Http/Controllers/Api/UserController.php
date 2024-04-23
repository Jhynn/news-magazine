<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\{
    UserDestroyRequest,
    UserShowRequest,
    UserStoreRequest,
    UserUpdateRequest
};
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use App\Traits\ApiCommonResponses;
use Throwable;

class UserController extends Controller
{
    use ApiCommonResponses;

    public function __construct(private UserService $service) { }

    /**
     * Display a listing of the resource.
     */
    public function index(UserShowRequest $request)
    {
        try {
            $payload = $this->service->index($request);

            return UserResource::collection($payload)->response();
        } catch (Throwable $th) {
            return $this->error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        try {
            $payload = $this->service->store($request->validated());

            return UserResource::make($payload->loadMissing(['topics', 'articles']))
                ->additional([
                    'message' => __(
                        'the :resource was :action', ['resource' => __('user'), 'action' => __('created')]
                    )
                ])->response();
        } catch (Throwable $th) {
            return $this->error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UserShowRequest $request, User $user)
    {
        try {
            $payload = $this->service->show($user);

            return UserResource::make($payload->loadMissing(['topics', 'articles']))
                ->response();
        } catch (Throwable $th) {
            return $this->error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        try {
            $payload = $this->service->update($request->validated(), $user);

            return UserResource::make($payload->loadMissing(['topics', 'articles']))
                ->additional([
                    'message' => __(
                        'the :resource was :action', ['resource' => __('user'), 'action' => __('updated')]
                    )
                ])->response();
        } catch (Throwable $th) {
            return $this->error($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserDestroyRequest $request, User $user)
    {
        try {
            $payload = $this->service->destroy($user);

            return UserResource::make($payload)
                ->additional([
                    'message' => __(
                        'the :resource was :action', ['resource' => __('user'), 'action' => __('deleted')]
                    )
                ])->response();
        } catch (Throwable $th) {
            return $this->error($th);
        }
    }

    public function me()
    {
        /** @var \App\Models\User $user*/
        $user = auth()->user();

        return UserResource::make($this->service->show($user));
    }
}
