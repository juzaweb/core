<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Http\Resources;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Juzaweb\Core\Support\Resources\ModelCollectionResource;
use App\Models\User;
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
