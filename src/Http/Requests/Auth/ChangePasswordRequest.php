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
 *     request="ChangePasswordRequest",
 *     description="Forgot Password Request",
 *     required=true,
 *      @OA\MediaType(
 *            mediaType="multipart/form-data",
 *            @OA\Schema(
 *                type="object",
 *                required={"current_password", "password", "password_confirmation"},
 *                @OA\Property(
 *                    property="current_password",
 *                    type="string",
 *                    description="User current password",
 *                    format="password"
 *                ),
 *                @OA\Property(
 *                     property="password",
 *                     type="string",
 *                     description="New password",
 *                      format="password"
 *                ),
 *                @OA\Property(
 *                      property="password_confirmation",
 *                      type="string",
 *                      description="Confirm new password",
 *                      format="password"
 *                 ),
 *            )
 *          )
 *      )
 *  )
 */
class ChangePasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
