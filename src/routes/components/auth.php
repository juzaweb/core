<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

use Juzaweb\Modules\Core\Http\Controllers\Auth\AuthController;
use Juzaweb\Modules\Core\Http\Controllers\Auth\SocialLoginController;
use Juzaweb\Modules\Core\Http\Middleware\VerifyToken;

Route::get('user/login', [AuthController::class, 'login'])
    ->name('login')
    ->middleware(['guest']);
Route::post('user/login', [AuthController::class, 'doLogin'])
    ->middleware(['throttle:5,1', 'guest', VerifyToken::class]);

Route::get('user/register', [AuthController::class, 'register'])
    ->name('register')
    ->middleware(['guest']);
Route::post('user/register', [AuthController::class, 'doRegister'])
    ->middleware(['throttle:5,1', 'guest', VerifyToken::class]);

Route::get('user/forgot-password', [AuthController::class, 'forgotPassword'])
    ->name('forgot-password')
    ->middleware(['guest']);
Route::post('user/forgot-password', [AuthController::class, 'doForgotPassword'])
    ->middleware(['throttle:5,1', 'guest', VerifyToken::class]);

Route::get('user/reset-password/{email}/{token}', [AuthController::class, 'resetPassword'])
    ->name('password.reset')
    ->middleware(['guest']);
Route::post('user/reset-password/{email}/{token}', [AuthController::class, 'doResetPassword'])
    ->middleware(['guest', VerifyToken::class]);

Route::get('user/verification', [AuthController::class, 'verificationNotice'])
    ->name('verification.notice')
    ->middleware(['auth']);
Route::post('user/verification/resend', [AuthController::class, 'resendVerification'])
    ->name('verification.resend')
    ->middleware(['auth', 'throttle:3,1', VerifyToken::class]);

Route::get('user/verification/{id}/{hash}', [AuthController::class, 'verification'])
    ->middleware(['signed'])
    ->name('verification.verify');

Route::post('user/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware(['auth']);

Route::get('user/social/{driver}/redirect', [SocialLoginController::class, 'redirect'])
    ->middleware(['guest'])
    ->name('auth.social.redirect');
Route::get('user/social/{driver}/callback', [SocialLoginController::class, 'callback'])
    ->middleware(['guest'])
    ->name('auth.social.callback');
