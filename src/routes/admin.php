<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Juzaweb\Core\Facades\RouteResource;
use Juzaweb\Core\Http\Controllers\Admin\SettingController;

Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

RouteResource::admin('users', UserController::class);

// RouteResource::admin('roles', UserController::class);

Route::get('/settings', [SettingController::class, 'index']);

Route::put('/settings', [SettingController::class, 'update']);
