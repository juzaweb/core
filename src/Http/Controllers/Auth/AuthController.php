<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Http\Controllers\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Juzaweb\Core\Http\Controllers\AdminController;
use Juzaweb\Core\Http\Requests\Auth\ForgotPasswordRequest;
use Juzaweb\Core\Http\Requests\Auth\LoginRequest;
use Juzaweb\Core\Http\Requests\Auth\RegisterRequest;
use Juzaweb\Core\Http\Requests\Auth\ResetPasswordRequest;
use Juzaweb\Core\Models\User;

class AuthController extends AdminController
{
    public function __construct(protected PasswordBroker $passwordBroker)
    {
    }

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
        abort_if(setting('user_registration') === false, 403, __('User registration is disabled.'));

        $socialLogins = $this->getSocialLoginProviders();

        return view('core::auth.register', [
            'title' => __('Register'),
            ...compact('socialLogins'),
        ]);
    }

    public function doRegister(RegisterRequest $request)
    {
        abort_if(setting('user_registration') === false, 403, __('User registration is disabled.'));

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

    public function verificationNotice()
    {
        return view('core::auth.verification-notice',
            [
                'title' => __('Email Verification'),
            ]
        );
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

    public function forgotPassword()
    {
        return view('core::auth.forgot-password',
            [
                'title' => __('Forgot Password'),
            ]
        );
    }

    public function doForgotPassword(ForgotPasswordRequest $request)
    {
        $user = User::findByEmail($request->post('email'));

        if ($user === null) {
            // Avoid email scanning attacks
            return $this->success('We have e-mailed your password reset link!');
        }

        DB::transaction(
            function () use ($user) {
                $token = $this->passwordBroker->createToken($user);

                $user->sendPasswordResetNotification($token);
            }
        );

        return $this->success('We have e-mailed your password reset link!');
    }

    public function resetPassword(string $email, string $token)
    {
        $user = $this->passwordBroker->getUser(['email' => $email]);

        abort_if($user === null, 404, __('Invalid password reset token.'));

        return view('core::auth.reset-password',
            [
                'title' => __('Reset Password'),
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
                $user->forceFill(['password' => Hash::make($password)]);

                $user->save();

                $user->passwordResets()->delete();

                activity()->causedBy($user)
                    ->performedOn($user)
                    ->event('change_password')
                    ->log('You reset password');

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return $this->error(__($status));
        }

        return $this->success(
            [
                'message' => __('Your password has been reset! Please login again.'),
                'redirect' => route('login'),
            ]
        );
    }

    protected function getSocialLoginProviders(): \Illuminate\Support\Collection
    {
        return collect(config('core.social_login.providers', []))
            ->map(fn($item, $key) => title_from_key($key))
            ->filter(fn ($item, $key) => setting("{$key}_login", false));
    }
}
