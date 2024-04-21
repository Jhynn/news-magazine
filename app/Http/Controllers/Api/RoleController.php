<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\{
    RoleDestroyRequest,
    RoleShowRequest,
    RoleStoreRequest,
    RoleUpdateRequest
};
use App\Http\Resources\RoleResource;
use App\Services\RoleService;
use App\Traits\ApiCommonResponses;
use App\Models\Role;
use Throwable;

class RoleController extends Controller
{
    use ApiCommonResponses;

    public function __construct(private RoleService $service) { }

    /**
     * Display a listing of the resource.
     */
    public function index(RoleShowRequest $request)
    {
        try {
            $payload = $this->service->index($request);

            return RoleResource::collection($payload)->response();
        } catch (Throwable $th) {
            return $this->error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleStoreRequest $request)
    {
        try {
            $payload = $this->service->store($request->validated());

            return RoleResource::make($payload->loadMissing(['permissions']))
                ->additional([
                    'message' => __(
                        'the :resource was :action', ['resource' => __('role'), 'action' => __('created')]
                    )
                ])->response();
        } catch (Throwable $th) {
            return $this->error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RoleShowRequest $request, Role $role)
    {
        try {
            $payload = $this->service->show($role);

            return RoleResource::make($payload->loadMissing(['permissions']))
                ->response();
        } catch (Throwable $th) {
            return $this->error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleUpdateRequest $request, Role $role)
    {
        try {
            $payload = $this->service->update($request->validated(), $role);

            return RoleResource::make($payload->loadMissing(['permissions']))
                ->additional([
                    'message' => __(
                        'the :resource was :action', ['resource' => __('role'), 'action' => __('updated')]
                    )
                ])->response();
        } catch (Throwable $th) {
            return $this->error($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoleDestroyRequest $request, Role $role)
    {
        try {
            $payload = $this->service->destroy($role);

            return RoleResource::make($payload)
                ->additional([
                    'message' => __(
                        'the :resource was :action', ['resource' => __('role'), 'action' => __('deleted')]
                    )
                ])->response();
        } catch (Throwable $th) {
            return $this->error($th);
        }
    }
}
