<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\StoreUserRequest;
use App\Http\Requests\Api\V1\User\UpdateUserRequest;
use App\Models\User;
use App\Services\V1\UserService;


class UserController extends Controller
{


    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        // Simple permission check or policy can be here if not in Request
        if (!auth()->user()->can('view users')) {
            return response()->apiError('Unauthorized', 403);
        }

        $users = $this->userService->getAllUsers();
        return response()->apiSuccess('Users Retrieved', $users);
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());
        return response()->apiSuccess('User Created Successfully', $user, 201);
    }

    public function show(User $user)
    {
        if (!auth()->user()->can('view users')) {
            return response()->apiError('Unauthorized', 403);
        }
        
        $data = $this->userService->getUser($user);
        return response()->apiSuccess('User Retrieved', $data);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $this->userService->updateUser($user, $request->validated());
        return response()->apiSuccess('User Updated Successfully', $data);
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->can('delete user')) {
             return response()->apiError('Unauthorized', 403);
        }

        $this->userService->deleteUser($user);
        return response()->apiSuccess('User Deleted Successfully');
    }
}
