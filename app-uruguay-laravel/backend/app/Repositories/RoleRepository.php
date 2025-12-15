<?php

namespace App\Repositories;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class RoleRepository extends BaseRepository
{
    /**
     * RoleRepository constructor.
     *
     * @param Role $role
     */
    public function __construct(Role $role)
    {
        parent::__construct($role);
    }


    /**
     * Get all roles with their permissions
     *
     * @return Collection
     */
    public function getAllWithPermissions(): Collection
    {
        return $this->model->with('permissions')->get();
    }


    /**
     * Get paginated roles with filters
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->query();

        // Apply filters
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('display_name', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['is_protected'])) {
            $query->where('is_protected', $filters['is_protected']);
        }

        return $query->with('permissions')->orderBy('name')->paginate($perPage);
    }


    /**
     * Find role by name
     *
     * @param string $name
     * @return Role|null
     */
    public function findByName(string $name): ?Role
    {
        return $this->model->where('name', $name)->first();
    }


    /**
     * Get protected roles
     *
     * @return Collection
     */
    public function getProtectedRoles(): Collection
    {
        return $this->model->where('is_protected', true)->get();
    }

    /**
     * Get non-protected roles
     *
     * @return Collection
     */
    public function getNonProtectedRoles(): Collection
    {
        return $this->model->where('is_protected', false)->get();
    }


    /**
     * Get role permissions
     *
     * @param int $roleId
     * @return Collection
     */
    public function getRolePermissions(int $roleId): Collection
    {
        $role = $this->find($roleId);
        return $role->permissions;
    }


    /**
     * Get role permission IDs
     *
     * @param int $roleId
     * @return array
     */
    public function getRolePermissionIds(int $roleId): array
    {
        return $this->getRolePermissions($roleId)->pluck('id')->toArray();
    }
}
