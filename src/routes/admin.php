<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

use Illuminate\Support\Facades\Route;
use Juzaweb\Core\Facades\RouteResource;
use Juzaweb\Core\Http\Controllers\Admin\DashboardController;
use Juzaweb\Core\Http\Controllers\Admin\LanguageController;
use Juzaweb\Core\Http\Controllers\Admin\PageController;
use Juzaweb\Core\Http\Controllers\Admin\ProfileController;
use Juzaweb\Core\Http\Controllers\Admin\SettingController;
use Juzaweb\Core\Http\Controllers\Admin\TranslationController;
use Juzaweb\Core\Http\Controllers\Admin\UserController;

// RouteResource::admin('roles', UserController::class);
Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/profile', [ProfileController::class, 'index'])->name('admin.profile');
Route::get('/profile/notification', [ProfileController::class, 'notification'])
    ->name('admin.profile.notification');
Route::post('/profile', [ProfileController::class, 'update']);

RouteResource::admin('users', UserController::class);

RouteResource::admin('languages', LanguageController::class)
    ->except(['edit', 'update', 'create']);
RouteResource::admin('pages', PageController::class);

Route::get('/languages/{language}/translations', [TranslationController::class, 'index'])
    ->name('admin.languages.translations')
    ->middleware(['permission:languages.index']);
Route::get('/languages/{language}/translations/get-data', [TranslationController::class, 'getDataCollection'])
    ->name('admin.languages.translations.get-data')
    ->middleware(['permission:languages.index']);
Route::put('/languages/{language}/translations', [TranslationController::class, 'update'])
    ->name('admin.languages.translations.update')
    ->middleware(['permission:languages.edit']);

Route::get('/settings/general', [SettingController::class, 'index'])
    ->name('admin.settings.general')
    ->middleware(['permission:settings.general.edit']);

Route::put('/settings', [SettingController::class, 'update'])
    ->middleware(['permission:settings.general.edit']);

Route::get('/settings/social-login', [SettingController::class, 'socialLogin'])
    ->name('admin.settings.social-login')
    ->middleware(['permission:settings.social-login.index']);
