<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Juzaweb\Core\Rules\AllExist;

class UserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->route('id'),
            'password' => [
                Rule::requiredIf(fn() => $this->isMethod('post')),
                'nullable', // Allow null for PUT/PATCH requests
                'string',
                'min:8', // Minimum 8 characters
                'confirmed', // Must match the password confirmation field
            ],
            'roles' => ['nullable', 'array', AllExist::make('roles', 'id')],
        ];
    }
}
