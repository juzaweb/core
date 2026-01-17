<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Juzaweb\Modules\Core\Permissions\Models\Role;

/**
 * @property-read Role $resource
 */
class RoleResource extends JsonResource
{
    public function toArray($request): array
    {
        $role = [
            'id' => $this->resource->id,
            'code' => $this->resource->code,
            'name' => $this->resource->name,
            'description' => $this->resource->description,
            'grant_all_permissions' => $this->resource->grant_all_permissions,
            'created_at' => $this->resource->created_at->toISOString(true),
            'updated_at' => $this->resource->updated_at->toISOString(true),
        ];

        if ($this->resource->relationLoaded('permissions')) {
            $role['permissions'] = PermissionResource::collection($this->resource->permissions);
        }

        return $role;
    }
}
