<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();

        return response()->json([
            'status' => 'success',
            'roles' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Role created successfully',
            'role' => $role->load('permissions')
        ], 201);
    }

    public function show(Role $role)
    {
        return response()->json([
            'status' => 'success',
            'role' => $role->load('permissions')
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ]);

        $role->update(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Role updated successfully',
            'role' => $role->load('permissions')
        ]);
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Role deleted successfully'
        ]);
    }
    
    public function permissions(Request $request)
    {
         $permissions = Permission::all();
         
         return response()->json([
            'status' => 'success',
            'permissions' => $permissions
         ]);
    }
}
