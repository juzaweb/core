<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

use Juzaweb\Core\Facades\RouteResource;
use Juzaweb\Core\Http\Controllers\Admin\DashboardController;
use Juzaweb\Core\Http\Controllers\Admin\SettingController;
use Juzaweb\Core\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

RouteResource::admin('users', UserController::class);

// RouteResource::admin('roles', UserController::class);

Route::get('/settings', [SettingController::class, 'index']);
