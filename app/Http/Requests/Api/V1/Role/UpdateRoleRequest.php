<?php

namespace App\Http\Requests\Api\V1\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit role');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                Rule::unique('roles')->ignore($this->route('role')),
            ],
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ];
    }
}
