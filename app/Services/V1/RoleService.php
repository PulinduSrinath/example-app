<?php

namespace App\Services\V1;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleService
{
    public function getAllRoles()
    {
        $roles = Role::with('permissions')->get();
        return \App\Http\Resources\V1\RoleResource::collection($roles);
    }

    public function createRole(array $data)
    {
        $role = Role::create(['name' => $data['name']]);

        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return new \App\Http\Resources\V1\RoleResource($role->load('permissions'));
    }

    public function getRole(Role $role)
    {
        return new \App\Http\Resources\V1\RoleResource($role->load('permissions'));
    }

    public function updateRole(Role $role, array $data)
    {
        $role->update(['name' => $data['name']]);

        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return new \App\Http\Resources\V1\RoleResource($role->load('permissions'));
    }

    public function deleteRole(Role $role)
    {
        $role->delete();
    }

    public function getAllPermissions()
    {
        $permissions = Permission::all();
        return \App\Http\Resources\V1\PermissionResource::collection($permissions);
    }
}
