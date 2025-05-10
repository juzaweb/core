<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

use Juzaweb\Core\Http\Controllers\Auth\LoginController;

Route::get('auth/login', [LoginController::class, 'index'])->name('login');
Route::post('auth/login', [LoginController::class, 'login'])->name('auth.login');

Route::get('auth/register', [LoginController::class, 'index'])->name('auth.register');
Route::post('auth/register', [LoginController::class, 'register']);
