<?php

namespace Juzaweb\Core\Http\Controllers\Auth;

use Juzaweb\Core\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Juzaweb\Core\Models\User;
use Illuminate\Support\Facades\Auth;
use Juzaweb\Core\Traits\ResponseMessage;

class LoginController extends Controller
{
    use ResponseMessage;

    public function index()
    {
        do_action('auth.login.index');
        
        //
        
        return view('juzaweb::auth.login', [
            'title' => trans('juzaweb::app.login')
        ]);
    }
    
    public function login(Request $request)
    {
        // Login handle action
        do_action('auth.login.handle', $request);
    
        // Validate login
        $request->validate([
            'email' => 'required|email|max:150',
            'password' => 'required|min:6|max:32',
        ]);
        
        $email = $request->post('email');
        $password = $request->post('password');
        $remember = filter_var($request->post('remember'), FILTER_VALIDATE_BOOLEAN);
        $user = User::whereEmail($email)->first(['status', 'is_admin']);
        
        if (empty($user)) {
            return $this->error([
                'message' => trans('juzaweb::message.login_form.login_failed')
            ]);
        }
        
        if ($user->status != 'active') {
            return $this->error([
                'message' => trans('juzaweb::message.login_form.user_is_banned')
            ]);
        }
        
        if (Auth::attempt([
            'email' => $email,
            'password' => $password
        ], $remember)) {
            do_action('auth.login.success', Auth::user());

            return $this->success([
                'message' => trans('juzaweb::app.login_successfully'),
                'redirect' => $user->is_admin ? route('admin.dashboard') : '/'
            ]);
        }
    
        do_action('auth.login.failed');
        
        return $this->error(trans('juzaweb::message.login_form.login_failed'));
    }
    
    public function logout()
    {
        if (Auth::check()) {
            Auth::logout();
        }
        
        return redirect()->to('/');
    }
}
