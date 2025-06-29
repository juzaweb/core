<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Http\Resources;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Juzaweb\Core\Models\User;
use Juzaweb\Core\Support\Resources\ModelCollectionResource;
use OpenApi\Annotations as OA;

/**
 * @property-read Collection<User> $collection
 *
 * @OA\Schema(
 *     schema="UserCollection",
 *     required={"id", "name", "email", "created_at", "updated_at"},
 *     properties={
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="name", type="string"),
 *         @OA\Property(property="email", type="string"),
 *         @OA\Property(property="created_at", type="string", format="date-time"),
 *         @OA\Property(property="updated_at", type="string", format="date-time"),
 *     }
 * )
 */
class UserCollection extends ModelCollectionResource
{
    public function toArray(Request $request): array
    {
        return $this->collection->map(fn ($user) => new UserResource($user))->toArray();
    }
}
