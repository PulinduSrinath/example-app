<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Role\StoreRoleRequest;
use App\Http\Requests\Api\V1\Role\UpdateRoleRequest;
use App\Services\V1\RoleService;
use App\Traits\ApiResponseTrait;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use ApiResponseTrait;

    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index()
    {
        if (!auth()->user()->can('view roles')) {
            return $this->error('Unauthorized', 403);
        }

        $roles = $this->roleService->getAllRoles();
        return $this->success('Roles Retrieved', $roles);
    }

    public function store(StoreRoleRequest $request)
    {
        $role = $this->roleService->createRole($request->validated());
        return $this->success('Role Created Successfully', $role, 201);
    }

    public function show(Role $role)
    {
        if (!auth()->user()->can('view roles')) {
            return $this->error('Unauthorized', 403);
        }

        $data = $this->roleService->getRole($role);
        return $this->success('Role Retrieved', $data);
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $data = $this->roleService->updateRole($role, $request->validated());
        return $this->success('Role Updated Successfully', $data);
    }

    public function destroy(Role $role)
    {
        if (!auth()->user()->can('delete role')) {
            return $this->error('Unauthorized', 403);
        }

        $this->roleService->deleteRole($role);
        return $this->success('Role Deleted Successfully');
    }

    public function permissions()
    {
        if (!auth()->user()->can('view roles')) {
             return $this->error('Unauthorized', 403);
        }

        $permissions = $this->roleService->getAllPermissions();
        return $this->success('Permissions Retrieved', $permissions);
    }
}
