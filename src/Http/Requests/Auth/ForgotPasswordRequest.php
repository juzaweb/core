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
 *     request="ForgotPasswordRequest",
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
 *                )
 *            )
 *      )
 *      )
 *  )
 */
class ForgotPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email:rfc', 'max:255'],
        ];
    }
}
