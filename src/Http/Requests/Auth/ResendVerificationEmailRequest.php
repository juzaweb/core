<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\RequestBody(
 *      request="ResendVerificationEmailRequest",
 *      required=true,
 *      @OA\MediaType(
 *          mediaType="multipart/form-data",
 *          @OA\Schema(
 *              required={"email"},
 *              @OA\Property(property="email", type="string", format="email"),
 *          )
 *      )
 * )
 */
class ResendVerificationEmailRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email:rfc', 'max:255'],
        ];
    }
}
