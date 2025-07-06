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
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use InvalidArgumentException;
use App\Models\User;
use Juzaweb\Core\Models\Users\UserSocialConnection;
use Laravel\Socialite\Contracts\User as SocialUser;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\AbstractProvider;

class SocialLoginController extends AdminController
{
    public function redirect(string $driver)
    {
        if (! in_array($driver, config('auth.providers.users.login_socials', []))) {
            return $this->error(__('Invalid provider :name', ['name' => Str::title($driver)]));
        }

        $redirectUrl = action([static::class, 'callback'], ['driver' => $driver]);

        try {
            $socialite = Socialite::driver($driver)->redirectUrl($redirectUrl);
        } catch (InvalidArgumentException $e) {
            return $this->error($e->getMessage());
        } catch (ClientException $e) {
            report($e);
            return $this->error(__('Invalid credentials provided.'));
        }

        return $socialite->redirect();
    }

    public function callback(string $driver): JsonResponse
    {
        if (! in_array($driver, config('auth.providers.users.login_socials'))) {
            return $this->restFail(__('Invalid provider'));
        }

        $redirectUrl = $this->getDriverRedirectUrl($driver);

        try {
            /** @var SocialUser $socicalUser */
            $socicalUser = Socialite::driver($driver)->redirectUrl($redirectUrl)->stateless()->user();
        } catch (InvalidArgumentException $e) {
            return $this->restFail($e->getMessage());
        } catch (ClientException $e) {
            report($e);
            return $this->restFail($e->getMessage());
        }

        $userSocial = UserSocialConnection::findByProvider($driver, $socicalUser->getId());

        if ($userSocial) {
            $user = $userSocial->user;

            if ($user->status === User::STATUS_INACTIVE) {
                return $this->restFail(__('Your account has been deactivated.'));
            }

            return $this->loginAndResponseWithToken($user, $driver);
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

        return $this->loginAndResponseWithToken($user, $driver);
    }

    protected function getProvider(string $method): ?AbstractProvider
    {
        $config = $this->getConfig($method);

        switch ($method) {
            case 'facebook':
                $provider = FacebookProvider::class;
                break;
            case 'google':
                $provider = GoogleProvider::class;
                break;
            case 'twitter':
                $provider = TwitterProvider::class;
                break;
            case 'linkedin':
                $provider = LinkedInProvider::class;
                break;
            case 'github':
                $provider = GithubProvider::class;
                break;
        }

        if (empty($provider)) {
            return null;
        }

        return Socialite::buildProvider(
            $provider,
            $config
        );
    }
}
