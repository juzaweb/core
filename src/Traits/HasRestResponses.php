<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use Juzaweb\Modules\Core\Support\Resources\ModelCollectionResource;
use Juzaweb\Modules\Core\Support\Resources\ModelResource;

trait HasRestResponses
{
    /**
     * Generate a JSON response for errors REST API call.
     *
     * @param string $message description
     * @param int $status description
     * @param array $with description
     * @return JsonResponse
     */
    public function restFail(string $message, int $status = 422, array $additional = []): JsonResponse
    {
        return response()->json(
            array_merge(['success' => false, 'message' => $message], $additional),
            $status
        );
    }

    /**
     * Generate a JSON response for a successful REST API call.
     *
     * @param  array|Arrayable|LengthAwarePaginator  $data  The data to include in the response.
     * @param  string|null  $message  The optional message to include in the response.
     * @param  int  $status  The HTTP status code for the response.
     * @param  array  $with  Additional data to merge into the response.
     * @return JsonResponse The JSON response object.
     */
    public function restSuccess(
        array|Arrayable|LengthAwarePaginator|null $data = [],
        ?string $message = null,
        int $status = 200,
        array $additional = []
    ): JsonResponse {
        if (is_null($data)) {
            return response()->json(
                array_merge(['success' => true, 'message' => $message], $additional),
                $status
            );
        }

        // Handle case where $data is a LengthAwarePaginator
        if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $model = $data->first();

            if ($model && method_exists(get_class($model), 'makeCollectionResource')) {
                return get_class($model)::makeCollectionResource($data)
                    ->additional(array_merge(['message' => $message, 'success' => true], $additional))
                    ->response()
                    ->setStatusCode($status);
            }

            return ModelCollectionResource::make($data)->additional(['message' => $message, 'success' => true])
                ->response()
                ->setStatusCode($status);
        }

        // Handle case where $data is a Collection
        if ($data instanceof \Illuminate\Database\Eloquent\Collection) {
            $model = $data->first();

            if ($model && method_exists(get_class($model), 'makeCollectionResource')) {
                return get_class($model)::makeCollectionResource($data)
                    ->additional(array_merge(['message' => $message, 'success' => true], $additional))
                    ->response()
                    ->setStatusCode($status);
            }

            return ModelCollectionResource::make($data)
                ->additional(['message' => $message, 'success' => true])
                ->response()
                ->setStatusCode($status);
        }

        // Handle case where $data is a Model
        if ($data instanceof \Illuminate\Database\Eloquent\Model) {
            if (method_exists(get_class($data), 'makeResource')) {
                return get_class($data)::makeResource($data)
                    ->additional(array_merge(['message' => $message, 'success' => true], $additional))
                    ->response()
                    ->setStatusCode($status);
            }

            return ModelResource::make($data)
                ->additional(array_merge(['message' => $message, 'success' => true], $additional))
                ->response()
                ->setStatusCode($status);
        }

        // Handle case where $data is a Collection
        // if ($data instanceof \Illuminate\Support\Collection) {
        //     return ModelCollectionResource::make($data)->additional(['message' => $message])->response()->setStatusCode($status);
        // }

        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        }

        return response()->json(
            array_merge(($message ? ['message' => $message] : []), ['success' => true, 'data' => $data], $additional),
            $status
        );
    }
}
