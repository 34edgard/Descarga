<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Services\RoleService;
use Illuminate\Http\Request;


class RoleController extends Controller
{
    /**
     * @var RoleService
     */
    protected RoleService $roleService;

    /**
     * DummyModel Constructor
     *
     * @param RoleService $roleService
     *
     */
    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }


    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return RoleResource::collection($this->roleService->getAll());
    }



    /**
     * Get all roles with pagination and filters.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllWithPaginate(Request $request): \Illuminate\Http\JsonResponse
    {
        $filters = [
            'search' => $request->input('search'),
            'sort_by' => $request->input('sort_by'),
            'sort_direction' => $request->input('sort_direction'),
            'per_page' => $request->input('per_page', 10),
        ];
        $response = $this->roleService->getPaginated($filters);

        return $this->success($response);
    }


    public function store(RoleRequest $request): RoleResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new RoleResource($this->roleService->create($request->validated()));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function show(int $id): RoleResource
    {
        return RoleResource::make($this->roleService->getById($id));
    }


    public function update(RoleRequest $request, int $id): RoleResource|\Illuminate\Http\JsonResponse
    {
        try {
            $response = new RoleResource($this->roleService->update($request->validated(), $id));
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->roleService->delete($id);
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    public function destroyMultiple(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->roleService->deleteMultiple($request->input('ids'));
            return $this->success(null);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }


    /**
     * Get role permissions.
     *
     * @param int $id
     * @return AnonymousResourceCollection
     */
    public function permissions(int $id): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return PermissionResource::collection($this->roleService->getRolePermissions($id));
    }

    /**
     * Sync permissions to role.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function syncPermissions(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                'permissions' => 'required|array',
                'permissions.*' => 'exists:permissions,id'
            ]);
            $response = $this->roleService->syncPermissions($id, $request->permissions);
            return $this->success($response);
        } catch (\Exception $exception) {
            report($exception);
            return $this->naturalFail();
        }
    }
}
