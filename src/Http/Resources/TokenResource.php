<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="TokenResource",
 *     required={"token_type", "expires_in", "expires_at", "access_token", "refresh_token"},
 *     properties={
 *         @OA\Property(property="token_type", type="string"),
 *         @OA\Property(property="expires_in", type="integer"),
 *         @OA\Property(property="expires_at", type="string", format="date-time"),
 *         @OA\Property(property="access_token", type="string"),
 *         @OA\Property(property="refresh_token", type="string"),
 *     }
 * )
 */
class TokenResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'token_type' => $this->resource->token_type,
            'expires_in' => $this->resource->expires_in,
            'expires_at' => now()->addSeconds($this->resource->expires_in)->toISOString(),
            'access_token' => $this->resource->access_token,
            'refresh_token' => $this->resource->refresh_token,
        ];
    }
}
