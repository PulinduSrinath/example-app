<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Services\V1\AuthService;

use Illuminate\Http\Request;

class AuthController extends Controller
{


    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        try {
            $data = $this->authService->login($request->validated());
            return response()->apiSuccess('User Logged In Successfully', $data);
        } catch (\Exception $e) {
            return response()->apiError($e->getMessage(), 401);
        }
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());
        return response()->apiSuccess('User Logged Out Successfully');
    }

    public function user(Request $request)
    {
        $user = $request->user();
        
        // Ensure permissions are loaded appropriately if needed for response
        return response()->apiSuccess('User Details', [
            'user' => new \App\Http\Resources\V1\UserResource($user),
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ]);
    }
}
