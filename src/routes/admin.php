<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

use Illuminate\Support\Facades\Route;
use Juzaweb\Core\Http\Controllers\Admin\SettingController;

// RouteResource::admin('roles', UserController::class);

Route::get('/settings', [SettingController::class, 'index']);

Route::put('/settings', [SettingController::class, 'update']);
