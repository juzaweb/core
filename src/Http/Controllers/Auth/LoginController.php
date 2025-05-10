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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Juzaweb\Core\Http\Controllers\Controller;
use Juzaweb\Core\Http\Requests\Auth\LoginRequest;
use Juzaweb\Core\Traits\HasSessionResponses;

class LoginController extends Controller
{
    use HasSessionResponses;

    public function index()
    {
        return view('core::auth.login', ['title' => __('Login')]);
    }

    public function login(LoginRequest $request)
    {
        $remember = $request->boolean('remember');

        if (Auth::attempt($request->safe()->only('email', 'password'), $remember)) {
            do_action('login.failed');
        }

        /**
         * @var User $user
         */
        $user = Auth::user();

        do_action('login.success', $user);

        return $this->success(
            [
                'message' => trans('cms::app.login_successfully'),
                'redirect' => $user->hasPermission() ? route('admin.dashboard') : '/',
            ]
        );
    }
}
