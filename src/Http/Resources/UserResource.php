<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Http\Resources;

use Illuminate\Http\Request;
use Juzaweb\Core\Support\Resources\ModelResource;
use App\Models\User;
use OpenApi\Annotations as OA;

/**
 * @property-read User $resource
 *
 * @OA\Schema(
 *     schema="UserResource",
 *     required={"id", "name", "email", "created_at", "updated_at"},
 *     properties={
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="name", type="string"),
 *         @OA\Property(property="email", type="string"),
 *         @OA\Property(property="created_at", type="string", format="date-time"),
 *         @OA\Property(property="updated_at", type="string", format="date-time"),
 *         @OA\Property(property="permissions", type="array", @OA\Items(type="string")),
 *         @OA\Property(property="roles", type="array", @OA\Items(type="string")),
 *         @OA\Property(property="has_all_permissions", type="boolean"),
 *     }
 * )
 */
class UserResource extends ModelResource
{
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        if (! $request->user()?->tokenCan('user.email')) {
            unset($data['email']);
        }

        if ($this->resource->relationLoaded('permissions')) {
            $data['permissions'] = $this->resource->getAllPermissions()->pluck('code')->toArray();
        }

        if ($this->resource->relationLoaded('roles')) {
            $data['roles'] = $this->resource->roles->pluck('code')->toArray();
        }

        $data['has_all_permissions'] = $this->resource->hasRoleAllPermissions();

        return $data;
    }
}
