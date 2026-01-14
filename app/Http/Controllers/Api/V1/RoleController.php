<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Role\StoreRoleRequest;
use App\Http\Requests\Api\V1\Role\UpdateRoleRequest;
use App\Services\V1\RoleService;

use Spatie\Permission\Models\Role;

class RoleController extends Controller
{


    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index()
    {
        if (!auth()->user()->can('view roles')) {
            return response()->apiError('Unauthorized', 403);
        }

        $roles = $this->roleService->getAllRoles();
        return response()->apiSuccess('Roles Retrieved', $roles);
    }

    public function store(StoreRoleRequest $request)
    {
        $role = $this->roleService->createRole($request->validated());
        return response()->apiSuccess('Role Created Successfully', $role, 201);
    }

    public function show(Role $role)
    {
        if (!auth()->user()->can('view roles')) {
            return response()->apiError('Unauthorized', 403);
        }

        $data = $this->roleService->getRole($role);
        return response()->apiSuccess('Role Retrieved', $data);
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $data = $this->roleService->updateRole($role, $request->validated());
        return response()->apiSuccess('Role Updated Successfully', $data);
    }

    public function destroy(Role $role)
    {
        if (!auth()->user()->can('delete role')) {
            return response()->apiError('Unauthorized', 403);
        }

        $this->roleService->deleteRole($role);
        return response()->apiSuccess('Role Deleted Successfully');
    }

    public function permissions()
    {
        if (!auth()->user()->can('view roles')) {
             return response()->apiError('Unauthorized', 403);
        }

        $permissions = $this->roleService->getAllPermissions();
        return response()->apiSuccess('Permissions Retrieved', $permissions);
    }
}
