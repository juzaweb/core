<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

use Juzaweb\Core\Http\Controllers\Auth\AuthController;
use Juzaweb\Core\Http\Controllers\Auth\SocialLoginController;

Route::get('user/login', [AuthController::class, 'login'])
    ->name('login')
    ->middleware(['guest']);
Route::post('user/login', [AuthController::class, 'doLogin'])
    ->name('auth.login')
    ->middleware(['throttle:5,1', 'guest']);

Route::get('user/register', [AuthController::class, 'register'])
    ->name('admin.register')
    ->middleware(['guest']);
Route::post('user/register', [AuthController::class, 'doRegister'])
    ->name('auth.register')
    ->middleware(['throttle:5,1', 'guest']);

Route::get('user/forgot-password', [AuthController::class, 'forgotPassword'])
    ->name('auth.forgot-password')
    ->middleware(['guest']);

Route::post('user/forgot-password', [AuthController::class, 'doForgotPassword'])
    ->middleware(['throttle:5,1', 'guest']);
Route::get('user/reset-password/{email}/{token}', [AuthController::class, 'resetPassword'])
    ->name('password.reset')
    ->middleware(['guest']);

Route::post('user/reset-password/{email}/{token}', [AuthController::class, 'doResetPassword'])
    ->middleware(['guest']);

Route::get('user/verification/{id}/{hash}', [AuthController::class, 'verification'])
    ->middleware(['signed'])
    ->name('verification.verify');

Route::post('user/logout', [AuthController::class, 'logout'])
    ->name('auth.logout')
    ->middleware(['auth']);

Route::get('user/social/{driver}/redirect', [SocialLoginController::class, 'redirect'])
    ->middleware(['guest'])
    ->name('auth.social.redirect');
Route::get('user/social/{driver}/callback', [SocialLoginController::class, 'callback'])
    ->middleware(['guest'])
    ->name('auth.social.callback');
