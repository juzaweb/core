<?php
namespace Juzaweb\Core\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\RequestBody(
 *      request="RegisterRequest",
 *      required=true,
 *      @OA\JsonContent(
 *          required={"email", "password", "name", "password_confirmation"},
 *          @OA\Property(property="name", type="string"),
 *          @OA\Property(property="email", type="string", format="email"),
 *          @OA\Property(property="password", type="string"),
 *          @OA\Property(property="password_confirmation", type="string"),
 *          @OA\Property(property="birthday", type="string", format="date"),
 *      )
 * )
 */
class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'birthday' => ['nullable', 'date_format:Y-m-d'],
            // 'avatar' => ['nullable', 'mimes:jpg,jpeg,png'],
            'email' => ['required', 'string', 'email:rfc', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }
}
