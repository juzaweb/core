<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Http\Requests\Auth;

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
 *                required={"password", "password_confirmation"},
 *                @OA\Property(
 *                     property="password",
 *                     type="string"
 *                 ),
 *              @OA\Property(
 *                property="password_confirmation",
 *                  type="string",
 *                  description="Confirm password",
 *              )
 *            )
 *      )
 *  )
 */
class ResetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
