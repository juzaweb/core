<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

use Juzaweb\Core\Http\Controllers\Admin\DashboardController;
use Juzaweb\Core\Http\Controllers\Admin\SettingController;

Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

Route::get('/settings', [SettingController::class, 'index']);
