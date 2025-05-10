<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

use Juzaweb\Core\Http\Controllers\Admin\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
