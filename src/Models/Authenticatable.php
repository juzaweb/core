<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Models;

use Illuminate\Auth\Authenticatable as IlluminateAuthenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable as AccessAuthorizable;

/**
 * App\Models\Authenticatable
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Authenticatable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Authenticatable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Authenticatable query()
 * @mixin \Eloquent
 */
class Authenticatable extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use IlluminateAuthenticatable,
        AccessAuthorizable,
        CanResetPassword,
        MustVerifyEmail;
}
