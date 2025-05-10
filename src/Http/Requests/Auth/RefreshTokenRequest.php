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
 *      request="RefreshTokenRequest",
 *      required=true,
 *      @OA\JsonContent(
 *          required={"refresh_token"},
 *          @OA\Property(property="refresh_token", type="string"),
 *      )
 * )
 */
class RefreshTokenRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'refresh_token' => ['required', 'string']
        ];
    }
}
