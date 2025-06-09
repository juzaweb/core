<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->route('id'),
            'password' => [
                Rule::requiredIf(function () {
                    return $this->isMethod('post');
                }),
                'nullable', // Allow null for PUT/PATCH requests
                'string',
                'min:8', // Minimum 8 characters
                'confirmed', // Must match the password confirmation field
            ],
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ];
    }
}
