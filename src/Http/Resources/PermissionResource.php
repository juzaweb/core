<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Juzaweb\Core\Models\Permissions\Permission;
use OpenApi\Annotations as OA;

/**
 * @property-read Permission $resource
 *
 * @OA\Schema(
 *      schema="PermissionResource",
 *      required={"code", "name", "created_at", "updated_at"},
 *      properties={
 *          @OA\Property(property="code", type="string"),
 *          @OA\Property(property="name", type="string"),
 *          @OA\Property(property="description", type="string"),
 *          @OA\Property(property="created_at", type="string", format="date-time"),
 *          @OA\Property(property="updated_at", type="string", format="date-time"),
 *      }
 *  )
 */
class PermissionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'code' => $this->resource->code,
            'name' => $this->resource->name,
            'description' => $this->resource->description,
            'group' => $this->resource->group,
            'created_at' => $this->resource->created_at->toISOString(true),
            'updated_at' => $this->resource->updated_at->toISOString(true),
        ];
    }
}
