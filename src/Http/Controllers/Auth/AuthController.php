<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Http\Controllers\Auth;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Juzaweb\Core\Http\Controllers\AdminController;
use Juzaweb\Core\Http\Requests\Auth\LoginRequest;
use Juzaweb\Core\Http\Requests\Auth\RegisterRequest;
use Juzaweb\Core\Models\User;

class AuthController extends AdminController
{
    public function login()
    {
        $socialLogins = $this->getSocialLoginProviders();

        return view('core::auth.login',
            [
                'title' => __('Login'),
                ...compact('socialLogins'),
            ]
        );
    }

    public function doLogin(LoginRequest $request)
    {
        $remember = $request->boolean('remember');

        if (! Auth::attempt($request->safe()->only('email', 'password'), $remember)) {
            do_action('login.failed');

            return $this->error(
                [
                    'message' => trans('Login failed'),
                ]
            );
        }

        /**
         * @var User $user
         */
        $user = Auth::user();

        do_action('login.success', $user);

        return $this->success(
            [
                'message' => trans('Login successfully'),
                'redirect' => $user->hasPermission() ? route('admin.dashboard') : '/',
            ]
        );
    }

    public function register()
    {
        $socialLogins = $this->getSocialLoginProviders();

        return view('core::auth.register', [
            'title' => __('Register'),
            ...compact('socialLogins'),
        ]);
    }

    public function doRegister(RegisterRequest $request)
    {
        $user = DB::transaction(fn () => $request->register());

        $redirect = route('login');
        if (setting('user_verification')) {
            $redirect = null;
        }

        return $this->success(
            [
                'message' => trans('Register successfully'),
                'redirect' => $redirect,
                'user' => ['id' => $user->id],
            ]
        );
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('login');
    }

    public function verification(string $id, string $hash)
    {
        $user = User::find($id);

        if ($user === null) {
            return $this->error(__('Invalid verification token.'));
        }

        if (! hash_equals((string) $user->getKey(), $id)) {
            return $this->error(__('Invalid verification token.'));
        }

        if (! hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            return $this->error(__('Invalid verification token.'));
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();

            event(new Verified($user));
        }

        do_action('user.verified', $user);

        return $this->success(
            [
                'message' => __('Email verified successfully.'),
                'redirect' => route('login'),
            ]
        );
    }

    protected function getSocialLoginProviders(): \Illuminate\Support\Collection
    {
        return collect(config('core.social_login.providers', []))
            ->map(
                function ($item, $key) {
                    return title_from_key($key);
                }
            )
            ->filter(fn ($item, $key) => setting("{$key}_login", false));
    }
}
