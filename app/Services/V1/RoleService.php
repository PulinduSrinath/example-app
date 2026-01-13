<?php

namespace App\Services\V1;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleService
{
    public function getAllRoles()
    {
        return Role::with('permissions:id,name')->get()->makeHidden(['guard_name', 'created_at', 'updated_at', 'pivot']);
    }

    public function createRole(array $data)
    {
        $role = Role::create(['name' => $data['name']]);

        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return $role->load('permissions:id,name')->makeHidden(['guard_name', 'created_at', 'updated_at']);
    }

    public function getRole(Role $role)
    {
        return $role->load('permissions:id,name')->makeHidden(['guard_name', 'created_at', 'updated_at']);
    }

    public function updateRole(Role $role, array $data)
    {
        $role->update(['name' => $data['name']]);

        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return $role->load('permissions:id,name')->makeHidden(['guard_name', 'created_at', 'updated_at']);
    }

    public function deleteRole(Role $role)
    {
        $role->delete();
    }

    public function getAllPermissions()
    {
        return Permission::select('id', 'name')->get()->makeHidden(['guard_name', 'created_at', 'updated_at']);
    }
}
