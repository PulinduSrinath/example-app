<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\StoreUserRequest;
use App\Http\Requests\Api\V1\User\UpdateUserRequest;
use App\Models\User;
use App\Services\V1\UserService;
use App\Traits\ApiResponseTrait;

class UserController extends Controller
{
    use ApiResponseTrait;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        // Simple permission check or policy can be here if not in Request
        if (!auth()->user()->can('view users')) {
            return $this->error('Unauthorized', 403);
        }

        $users = $this->userService->getAllUsers();
        return $this->success('Users Retrieved', $users);
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());
        return $this->success('User Created Successfully', $user, 201);
    }

    public function show(User $user)
    {
        if (!auth()->user()->can('view users')) {
            return $this->error('Unauthorized', 403);
        }
        
        $data = $this->userService->getUser($user);
        return $this->success('User Retrieved', $data);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $this->userService->updateUser($user, $request->validated());
        return $this->success('User Updated Successfully', $data);
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->can('delete user')) {
             return $this->error('Unauthorized', 403);
        }

        $this->userService->deleteUser($user);
        return $this->success('User Deleted Successfully');
    }
}
