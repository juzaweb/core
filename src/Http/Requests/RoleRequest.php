<?php

namespace Juzaweb\Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
{
    public function rules(): array
    {
        $id = $this->route('role')?->id ?? $this->route('role');

        return [
            'name' => ['required', 'string', 'max:100'],
            'code' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique('roles', 'code')->ignore($id)
            ],
            'permissions' => ['nullable', 'array'],
        ];
    }
}
