<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

use Juzaweb\Core\Http\Controllers\Auth\AuthController;

Route::get('admin-cp/login', [AuthController::class, 'login'])->name('login');
Route::post('auth/login', [AuthController::class, 'doLogin'])->name('auth.login')
    ->middleware(['throttle:5,1']);

Route::get('admin-cp/register', [AuthController::class, 'register'])->name('admin.register');
Route::post('auth/register', [AuthController::class, 'doRegister'])
    ->name('auth.register')
    ->middleware(['throttle:5,1']);

Route::post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
