<?php

namespace App\Services\V1;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getAllUsers()
    {
        $users = User::with('roles')->get();
        return \App\Http\Resources\V1\UserResource::collection($users);
    }

    public function createUser(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if (isset($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return new \App\Http\Resources\V1\UserResource($user->load('roles'));
    }

    public function getUser(User $user)
    {
        return new \App\Http\Resources\V1\UserResource($user->load('roles', 'permissions'));
    }

    public function updateUser(User $user, array $data)
    {
        $user->update($data);

        if (isset($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return new \App\Http\Resources\V1\UserResource($user->load('roles'));
    }

    public function deleteUser(User $user)
    {
        $user->delete();
    }
}
