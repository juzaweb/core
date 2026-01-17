<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 * @property-read \Juzaweb\Modules\Core\Models\Media $resource
 */
class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'readable_size' => $this->resource->readable_size,
            'type' => $this->resource->type,
            'mime_type' => $this->resource->mime_type,
            'size' => $this->resource->size,
            'path' => $this->resource->path,
            'url' => $this->resource->url,
            'extension' => $this->resource->extension,
            'disk' => $this->resource->disk,
            'is_directory' => $this->resource->is_directory,
            'is_image' => $this->resource->is_image,
            'is_video' => $this->resource->is_video,
            'conversions' => $this->resource->getConversionResponse(),
            'metadata' => $this->resource->metadata,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
