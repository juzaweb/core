<?php

namespace Juzaweb\Core\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\RequestBody(
 *      request="LoginRequest",
 *      required=true,
 *      @OA\MediaType(
 *          mediaType="multipart/form-data",
 *          @OA\Schema(
 *              required={"email", "password"},
 *              @OA\Property(property="email", type="string", format="email"),
 *              @OA\Property(property="password", type="string"),
 *              @OA\Property(property="captcha", type="string", description="(Optional) ReCaptcha Token"),
 *          )
 *      )
 * )
 */
class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email:rfc,dns'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ];
    }
}
