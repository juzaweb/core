<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Http\Controllers\Auth;

use Juzaweb\Core\Http\Controllers\AdminController;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Juzaweb\Core\Models\Enums\UserStatus;
use Juzaweb\Core\Models\User;
use Juzaweb\Core\Models\Users\UserSocialConnection;
use Laravel\Socialite\Contracts\User as SocialUser;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\AbstractProvider;

class SocialLoginController extends AdminController
{
    public function redirect(string $driver)
    {
        if (! setting()->boolean("{$driver}_login", false)) {
            return $this->error(__('Invalid provider :name', ['name' => Str::title($driver)]));
        }

        $redirectUrl = action([static::class, 'callback'], ['driver' => $driver]);

        try {
            $socialite = $this->getProvider($driver)->redirectUrl($redirectUrl);
        } catch (InvalidArgumentException $e) {
            return $this->error($e->getMessage());
        } catch (ClientException $e) {
            report($e);
            return $this->error(__('Invalid credentials provided.'));
        }

        return $socialite->redirect();
    }

    public function callback(string $driver)
    {
        if (! setting()->boolean("{$driver}_login", false)) {
            return $this->error(__('Invalid provider :name', ['name' => Str::title($driver)]));
        }

        $redirectUrl = action([static::class, 'callback'], ['driver' => $driver]);

        try {
            /** @var SocialUser $socicalUser */
            $socicalUser = $this->getProvider($driver)->redirectUrl($redirectUrl)->user();
        } catch (InvalidArgumentException $e) {
            return $this->error($e->getMessage());
        } catch (ClientException $e) {
            report($e);
            return $this->error($e->getMessage());
        }

        $userSocial = UserSocialConnection::findByProvider($driver, $socicalUser->getId());

        if ($userSocial) {
            $user = $userSocial->user;

            if ($userSocial->user->status === UserStatus::BANNED) {
                return $this->error(__('Your account has been deactivated.'));
            }

            return $this->loginSuccess($user, $driver);
        }

        $user = DB::transaction(
            function () use ($socicalUser, $driver) {
                $password = Str::random(15);

                /** @var User $user */
                $user = User::firstOrCreate([
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
        return $this->success(
            [
                'redirect' => route('home'),
                'message' => __('Login successful.'),
            ]
        );
    }

    protected function getProvider(string $method): AbstractProvider
    {
        $provider = config("core.social_login.providers.{$method}");

        if (empty($provider)) {
            abort(404, __('Page not found'));
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
