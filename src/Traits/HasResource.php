<?php

namespace Juzaweb\Core\Traits;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Juzaweb\Core\Support\Resources\ModelCollectionResource;
use Juzaweb\Core\Support\Resources\ModelResource;

trait HasResource
{
    /**
     * Get the resource class for the model.
     *
     * @return class-string<JsonResource>
     */
    public static function getResource(): string
    {
        return ModelResource::class;
    }

    /**
     * Get the collection resource class for the model.
     *
     * @return class-string<ResourceCollection>
     */
    public static function getCollectionResource(): string
    {
        return ModelCollectionResource::class;
    }

    /**
     * Make a new resource collection.
     *
     * @param mixed $resource
     * @return JsonResource
     */
    public static function makeResource(mixed $resource): JsonResource
    {
        return static::getResource()::make($resource);
    }

    /**
     * Make a new resource collection.
     *
     * If the resource is not a CollectionResource and the resource class
     * is not a ModelResource, then return a new CollectionResource with
     * the given resource.
     *
     * If the resource is a CollectionResource or the resource class is a
     * ModelResource, then return a new resource collection using the
     * resource class.
     *
     * @param  mixed  $resource
     * @return ResourceCollection
     */
    public static function makeCollectionResource(mixed $resource): ResourceCollection
    {
        // If the resource is not a CollectionResource and the resource class
        // is not a ModelResource, then return a new CollectionResource with
        // the given resource.
        if (static::getCollectionResource() === ModelCollectionResource::class
            && static::getResource() !== ModelResource::class
        ) {
            return static::getResource()::collection($resource);
        }

        // If the resource is a CollectionResource or the resource class is a
        // ModelResource, then return a new resource collection using the
        // resource class.
        return static::getCollectionResource()::make($resource);
    }
}
