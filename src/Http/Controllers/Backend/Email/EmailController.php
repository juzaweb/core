<?php

namespace Juzaweb\Core\Http\Controllers\Backend\Email;

use Juzaweb\Core\Http\Controllers\BackendController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Juzaweb\Core\Email\EmailService;

class EmailController extends BackendController
{
    public function index()
    {
        $config = get_config('email', []);
        return view('juzaweb::backend.email.index', [
            'title' => trans('juzaweb::app.email_setting'),
            'config' => $config,
        ]);
    }
    
    public function save(Request $request)
    {
        $email = $request->post('email');
        set_config('email', $email);
        
        return $this->success([
            'message' => trans('juzaweb::app.save_successfully')
        ]);
    }
    
    public function sendTestMail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        
        $email = $request->post('email');
        EmailService::make()
            ->setEmails($email)
            ->setSubject('Send email test for {name}')
            ->setBody('Hello {name}, This is the test email')
            ->setParams(['name' => Auth::user()->name])
            ->send();

        return $this->success([
            'message' => trans('juzaweb::app.send_mail_successfully')
        ]);
    }
}
