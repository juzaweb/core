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

Route::get('admin-cp/login', [AuthController::class, 'login'])
    ->name('login')
    ->middleware(['guest']);
Route::post('auth/login', [AuthController::class, 'doLogin'])
    ->name('auth.login')
    ->middleware(['throttle:5,1', 'guest']);

Route::get('admin-cp/register', [AuthController::class, 'register'])
    ->name('admin.register')
    ->middleware(['guest']);
Route::post('auth/register', [AuthController::class, 'doRegister'])
    ->name('auth.register')
    ->middleware(['throttle:5,1', 'guest']);

Route::post('auth/logout', [AuthController::class, 'logout'])
    ->name('auth.logout')
    ->middleware(['auth']);

Route::get('auth/social/{driver}/redirect', [SocialLoginController::class, 'redirect'])
    ->middleware(['guest'])
    ->name('auth.social.redirect');
Route::get('auth/social/{driver}/callback', [SocialLoginController::class, 'callback'])
    ->middleware(['guest'])
    ->name('auth.social.callback');
