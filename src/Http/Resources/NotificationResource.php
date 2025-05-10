<?php

namespace Juzaweb\Core\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Juzaweb\Core\Models\Notification;
use OpenApi\Annotations as OA;

/**
 * @property-read Notification $resource
 *
 * @OA\Schema(
 *      schema="NotificationResource",
 *      properties={
 *          @OA\Property(property="title", type="string"),
 *          @OA\Property(property="type", type="string"),
 *          @OA\Property(property="data", type="object"),
 *          @OA\Property(property="read", type="boolean"),
 *          @OA\Property(property="read_at", type="string", format="date-time"),
 *          @OA\Property(property="created_at", type="string", format="date-time"),
 *          @OA\Property(property="updated_at", type="string", format="date-time"),
 *      }
 *  )
 */
class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'data' => $this->resource->data,
            'read_at' => $this->resource->read_at,
            'read' => $this->resource->read_at !== null,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
