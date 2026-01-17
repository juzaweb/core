<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Http\Resources;

use Illuminate\Http\Request;
use Juzaweb\Modules\Core\Support\Resources\ModelResource;
use OpenApi\Annotations as OA;

/**
 * @property-read \Juzaweb\Modules\Core\Translations\Models\Language $resource
 *
 * @OA\Schema(
 *       schema="LanguageResource",
 *       properties={
 *           @OA\Property(property="code", type="string"),
 *           @OA\Property(property="name", type="string"),
 *           @OA\Property(property="default", type="boolean"),
 *           @OA\Property(property="created_at", type="string", format="date-time"),
 *           @OA\Property(property="updated_at", type="string", format="date-time"),
 *       }
 *   )
 */
class LanguageResource extends ModelResource
{
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->resource->code,
            'name' => $this->resource->name,
            'default' => $this->resource->default,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
