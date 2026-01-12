<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/user', function (Request $request) {
        $user = $request->user();
        $user->getPermissionsViaRoles();
        return response()->json([
            'user' => $user,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ]);
    });

    // User Management Routes
    Route::group(['middleware' => ['can:view users']], function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{user}', [UserController::class, 'show']);
    });

    Route::post('/users', [UserController::class, 'store'])->middleware('can:create user');
    Route::put('/users/{user}', [UserController::class, 'update'])->middleware('can:edit user');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('can:delete user');

    // Role Management Routes
    Route::group(['middleware' => ['can:view roles']], function () {
        Route::get('/roles', [RoleController::class, 'index']);
        Route::get('/roles/{role}', [RoleController::class, 'show']);
        Route::get('/permissions', [RoleController::class, 'permissions']);
    });

    Route::post('/roles', [RoleController::class, 'store'])->middleware('can:create role');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->middleware('can:edit role');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->middleware('can:delete role');
});
