<?php

namespace App\Http\Requests\Api\V1\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit user');
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'email' => [
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->route('user')), // Ignore current user
            ],
            'roles' => 'array',
            'roles.*' => 'exists:roles,name',
        ];
    }
}
