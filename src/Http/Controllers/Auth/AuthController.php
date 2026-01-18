<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Http\Controllers\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\View;
use Juzaweb\Modules\Admin\Models\User;
use Juzaweb\Modules\Core\Facades\Theme;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;
use Juzaweb\Modules\Core\Http\Requests\Auth\ForgotPasswordRequest;
use Juzaweb\Modules\Core\Http\Requests\Auth\LoginRequest;
use Juzaweb\Modules\Core\Http\Requests\Auth\RegisterRequest;
use Juzaweb\Modules\Core\Http\Requests\Auth\ResetPasswordRequest;

class AuthController extends AdminController
{
    public function __construct(protected PasswordBroker $passwordBroker)
    {
    }

    public function login()
    {
        $socialLogins = social_login_providers();

        return view($this->getViewName('login'),
            [
                'title' => __('core::translation.login'),
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
                    'message' => trans('core::translation.authfailed'),
                ]
            );
        }

        $user = Auth::user();

        $user->logActivity()
            ->event('logined')
            ->log(__('core::translation.logged_in_to_the_system'));

        do_action('login.success', $user);

        return $this->success(
            [
                'message' => trans('core::translation.login_successfully'),
                'redirect' => home_url(),
            ]
        );
    }

    public function register()
    {
        abort_if(setting('user_registration') === false, 403, __('core::translation.user_registration_is_disabled'));

        $socialLogins = social_login_providers();

        return view(
            $this->getViewName('register'),
            [
                'title' => __('core::translation.register'),
                ...compact('socialLogins'),
            ]
        );
    }

    public function doRegister(RegisterRequest $request)
    {
        abort_if(setting('user_registration') === false, 403, __('core::translation.user_registration_is_disabled'));

        $user = DB::transaction(fn () => $request->register());

        $redirect = route('login');
        if (setting('user_verification')) {
            $redirect = null;
        }

        return $this->success(
            [
                'message' => trans('core::translation.register_successfully'),
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

    public function verificationNotice()
    {
        return view($this->getViewName('verification-notice'),
            [
                'title' => __('core::translation.email_verification'),
            ]
        );
    }

    public function resendVerification()
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return $this->success(
                [
                    'message' => __('core::translation.email_is_already_verified'),
                ]
            );
        }

        $user->sendEmailVerificationNotification();

        return $this->success(
            [
                'message' => __('core::translation.a_new_verification_link_has_been_sent_to_your_email_address'),
            ]
        );
    }

    public function verification(string $id, string $hash)
    {
        $user = User::find($id);

        if ($user === null) {
            return $this->error(__('core::translation.invalid_verification_token'));
        }

        if (! hash_equals((string) $user->getKey(), $id)) {
            return $this->error(__('core::translation.invalid_verification_token'));
        }

        if (! hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            return $this->error(__('core::translation.invalid_verification_token'));
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();

            event(new Verified($user));
        }

        do_action('user.verified', $user);

        return $this->success(
            [
                'message' => __('core::translation.email_verified_successfully'),
                'redirect' => route('login'),
            ]
        );
    }

    public function forgotPassword()
    {
        return view($this->getViewName('forgot-password'),
            [
                'title' => __('core::translation.forgot_password'),
            ]
        );
    }

    public function doForgotPassword(ForgotPasswordRequest $request)
    {
        $user = User::findByEmail($request->post('email'));

        if ($user === null) {
            // Avoid email scanning attacks
            return $this->success(__('core::translation.we_have_e_mailed_your_password_reset_link'));
        }

        DB::transaction(
            function () use ($user) {
                $token = $this->passwordBroker->createToken($user);

                $user->sendPasswordResetNotification($token);

                $user->logActivity()
                    ->performedOn($user)
                    ->event('forgot_password')
                    ->log(__('core::translation.requested_password_reset'));
            }
        );

        return $this->success(__('core::translation.we_have_e_mailed_your_password_reset_link'));
    }

    public function resetPassword(string $email, string $token)
    {
        /** @var User $user */
        $user = $this->passwordBroker->getUser(['email' => $email]);

        abort_if($user === null, 404, __('core::translation.invalid_password_reset_token'));

        return view($this->getViewName('reset-password'),
            [
                'title' => __('core::translation.reset_password'),
                'token' => $token,
                'email' => $user->email,
            ]
        );
    }

    public function doResetPassword(ResetPasswordRequest $request, string $email, string $token)
    {
        $status = Password::reset(
            $request->merge(['token' => $token, 'email' => $email])
                ->only(['token', 'email', 'password', 'password_confirmation']),
            function ($user, $password) {
                /** @var User $user */
                $user->forceFill(['password' => Hash::make($password)]);

                $user->save();

                $user->passwordResets()->delete();

                $user->logActivity()
                    ->performedOn($user)
                    ->event('change_password')
                    ->log(__('core::translation.reset_password_by_email'));

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return $this->error(__($status));
        }

        return $this->success(
            [
                'message' => __('core::translation.your_password_has_been_reset_please_login_again'),
                'redirect' => route('login'),
            ]
        );
    }

    protected function getViewName(string $name): string
    {
        $theme = Theme::current();

        if (View::exists($theme->name() . '::auth.'. $name)) {
            return $theme->name() . '::auth.'. $name;
        }

        return 'core::auth.'. $name;
    }
}
