<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\RequestBody(
 *     request="ResetPasswordRequest",
 *     description="Forgot Password Request",
 *     required=true,
 *      @OA\MediaType(
 *            mediaType="multipart/form-data",
 *            @OA\Schema(
 *                type="object",
 *                required={"email"},
 *                @OA\Property(
 *                    property="email",
 *                    type="string",
 *                    description="User email",
 *                    format="email"
 *                ),
 *                @OA\Property(
 *                     property="password",
 *                     type="string"
 *                 ),
 *            )
 *      )
 *  )
 */
class ResetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email:rfc', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
