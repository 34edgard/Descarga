<?php

namespace App\Services;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Repositories\RoleRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class RoleService
{
    /**
     * @var RoleRepository
     */
    protected $roleRepository;

    /**
     * RoleService constructor.
     *
     * @param RoleRepository $roleRepository
     */
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }


    /**
     * Get all roles
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->roleRepository->all();
    }


    /**
     * Get all roles with permissions
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllWithPermissions()
    {
        return $this->roleRepository->getAllWithPermissions();
    }


    /**
     * Get role by ID
     *
     * @param int $id
     * @return Role
     */
    public function getById(int $id)
    {
        return $this->roleRepository->find($id)->load('permissions');
    }


    /**
     * Create a new role
     *
     * @param array $data
     * @return Role
     * @throws \Exception
     */
    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $role = $this->roleRepository->create([
                'name'          => $data['name'],
                'guard_name'    => 'web',
                'is_protected'  => $data['is_protected'] ?? false,
                'description'   => $data['description'] ?? null,
            ]);

            // Sync permissions if provided
            if (isset($data['permissions']) && is_array($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }

            DB::commit();
            return $role;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating role: ' . $e->getMessage());
            throw new InvalidArgumentException('Unable to create role');
        }
    }


    /**
     * Update role
     *
     * @param array $data
     * @param int $id
     * @return Role
     * @throws \Exception
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();

        try {
            $role = $this->roleRepository->update([
                'name'          => $data['name'],
                'description'   => $data['description'],
                'is_active'     => $data['is_active'],
            ], $id);

            // Sync permissions if provided
            if (isset($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }

            DB::commit();
            return $role;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating role: ' . $e->getMessage());
            throw new InvalidArgumentException('Unable to update role');
        }
    }


    /**
     * Delete role by ID
     *
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function delete(int $id)
    {
        $role = $this->getById($id);

        // Prevent deletion of protected roles
        if ($role->is_protected) {
            throw new InvalidArgumentException('Protected roles cannot be deleted');
        }

        DB::beginTransaction();

        try {
            // Remove all permissions first
            $role->syncPermissions([]);

            $result = $this->roleRepository->delete($id);
            DB::commit();
            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting role: ' . $e->getMessage());
            throw new InvalidArgumentException('Unable to delete role');
        }
    }


    /**
     * Sync permissions to role
     *
     * @param int $roleId
     * @param array $permissionIds
     * @return void
     */
    public function syncPermissions(int $roleId, array $permissionIds): void
    {
        $this->getById($roleId);
        $permissionService = app(PermissionService::class);
        $permissionService->syncToRole($roleId, $permissionIds);
    }


    /**
     * Get paginated roles
     *
     * @param array $filters
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginated(array $filters = [])
    {
        $paginatedData = $this->roleRepository->allPaginated($filters);

        return [
            'data' => RoleResource::collection($paginatedData->items()),
            'meta' => [
                'total'         => $paginatedData->total(),
                'current_page'  => $paginatedData->currentPage(),
                'last_page'     => $paginatedData->lastPage(),
                'per_page'      => $paginatedData->perPage(),
            ],
        ];
    }


    /**
     * Get role permissions
     *
     * @param int $roleId
     * @return \Illuminate\Support\Collection
     */
    public function getRolePermissions(int $roleId)
    {
        return $this->roleRepository->getRolePermissions($roleId);
    }


    /**
     * Get role permission IDs
     *
     * @param int $roleId
     * @return array
     */
    public function getRolePermissionIds(int $roleId): array
    {
        return $this->roleRepository->getRolePermissionIds($roleId);
    }


    /**
     * Check if role is protected
     *
     * @param int $roleId
     * @return bool
     */
    public function isProtected(int $roleId): bool
    {
        $role = $this->getById($roleId);
        return $role->is_protected;
    }


    /**
     * Delete role by ids.
     *
     * @param $id
     * @return String
     */
    public function deleteMultiple(array $ids)
    {
        DB::beginTransaction();
        try {
            $response = $this->roleRepository->deleteMultiple($ids);
            DB::commit();
            return $response;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }
}
