<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Juzaweb\Core\Http\Controllers\AdminController;
use Juzaweb\Core\Http\Requests\Auth\LoginRequest;
use Juzaweb\Core\Http\Requests\Auth\RegisterRequest;

class AuthController extends AdminController
{
    public function login()
    {
        return view('core::auth.login', ['title' => __('Login')]);
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
        return view('core::auth.register', ['title' => __('Register')]);
    }

    public function doRegister(RegisterRequest $request)
    {
        $user = $request->register();

        return $this->success(
            [
                'message' => trans('Register successfully'),
                'redirect' => '/',
                'user' => ['id' => $user->id],
            ]
        );
    }

    public function logout(): \Illuminate\Http\RedirectResponse
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
