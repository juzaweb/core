<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Http\Controllers\Auth;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Juzaweb\Modules\Admin\Enums\UserStatus;
use Juzaweb\Modules\Admin\Models\User;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;
use Laravel\Socialite\Contracts\User as SocialUser;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\AbstractProvider;
use function Juzaweb\Modules\Admin\Http\Controllers\Auth\website;

class SocialLoginController extends AdminController
{
    public function redirect(string $driver)
    {
        if (! setting()->boolean("{$driver}_login", false)) {
            return $this->error(__('core::translation.invalid_provider_name', ['name' => Str::title($driver)]));
        }

        $redirectUrl = action([static::class, 'callback'], ['driver' => $driver]);

        try {
            $socialite = $this->getProvider($driver)->redirectUrl($redirectUrl);
        } catch (InvalidArgumentException $e) {
            return $this->error($e->getMessage());
        } catch (ClientException $e) {
            report($e);
            return $this->error(__('core::translation.invalid_credentials_provided'));
        }

        if (($redirect = request('redirect')) && is_internal_url($redirect)) {
            session()->put('auth_redirect', $redirect);
        }

        return $socialite->redirect();
    }

    public function callback(string $driver)
    {
        if (! setting()->boolean("{$driver}_login", false)) {
            return $this->error(__('core::translation.invalid_provider_name', ['name' => Str::title($driver)]));
        }

        $redirectUrl = action([static::class, 'callback'], ['driver' => $driver]);

        try {
            /** @var SocialUser $socicalUser */
            $socicalUser = $this->getProvider($driver)->redirectUrl($redirectUrl)->user();
        } catch (InvalidArgumentException $e) {
            return $this->error(
                [
                    'message' => $e->getMessage(),
                    'redirect' => route('login'),
                ]
            );
        } catch (ClientException $e) {
            report($e);
            return $this->error(
                [
                    'message' => __('core::translation.invalid_credentials_provided'),
                    'redirect' => route('login'),
                ]
            );
        }

        $guard = 'web';
        $provider = config("auth.guards.{$guard}.provider");
        $model = config('auth.providers.' . $provider . '.model');
        $connectionModel = config('auth.providers.' . $provider . '.social_connection_model');
        $userSocial = $connectionModel::findByProvider($driver, $socicalUser->getId());

        if ($userSocial && ($user = $userSocial->user)) {
            if ($userSocial->user->status === UserStatus::BANNED) {
                return $this->error(
                    [
                        'message' => __('core::translation.your_account_has_been_deactivated'),
                        'redirect' => route('login'),
                    ]
                );
            }

            return $this->loginSuccess($user, $driver);
        }

        $user = DB::transaction(
            function () use ($socicalUser, $driver, $model) {
                $password = Str::random(15);

                /** @var User $user */
                $user = $model::firstOrCreate([
                    'email' => $socicalUser->getEmail(),
                ], [
                    'name' => $socicalUser->getName(),
                    'password' => Hash::make($password),
                ]);

                if (! $user->hasVerifiedEmail()) {
                    $user->markEmailAsVerified();
                }

                $user->socialConnections()->create([
                    'provider' => $driver,
                    'provider_id' => $socicalUser->getId(),
                    'provider_data' => [
                        'name' => $socicalUser->getName(),
                        'email' => $socicalUser->getEmail(),
                        'avatar' => $socicalUser->getAvatar(),
                        'nickname' => $socicalUser->getNickname(),
                    ],
                ]);

                if ($user->wasRecentlyCreated) {
                    event(new Registered($user));
                }

                return $user;
            }
        );

        return $this->loginSuccess($user, $driver);
    }

    protected function loginSuccess($user, $driver)
    {
        Auth::login($user, true);

        $redirect = home_url();
        if (($redirectUrl = session()->pull('auth_redirect')) && is_internal_url($redirectUrl)) {
            $redirect = $redirectUrl;
        }

        return $this->success(
            [
                'redirect' => $redirect,
                'message' => __('core::translation.login_successful'),
            ]
        );
    }

    protected function getProvider(string $method): AbstractProvider
    {
        $provider = config("core.social_login.providers.{$method}");

        if (empty($provider)) {
            abort(404, __('core::translation.page_not_found'));
        }

        $config = [
            'client_id' => setting("{$method}_client_id"),
            'client_secret' => setting("{$method}_client_secret"),
            'redirect' => action([static::class, 'callback'], ['driver' => $method]),
        ];

        return Socialite::buildProvider(
            $provider,
            $config
        );
    }
}
