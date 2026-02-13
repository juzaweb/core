<?php

namespace Juzaweb\Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Juzaweb\Modules\Core\Permissions\Models\Role;

class RoleRequest extends FormRequest
{
    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'name' => ['required', 'string', 'max:100'],
            'code' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique((new Role())->getTable(), 'code')->ignore($id)
            ],
            'permissions' => ['nullable', 'array'],
        ];
    }
}
