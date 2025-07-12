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
use Juzaweb\Core\Http\Controllers\Admin\LanguageController;
use Juzaweb\Core\Http\Controllers\Admin\SettingController;
use Juzaweb\Core\Http\Controllers\Admin\TranslationController;

// RouteResource::admin('roles', UserController::class);

RouteResource::admin('languages', LanguageController::class)->except(['edit', 'update', 'create']);

Route::get('/languages/{language}/translations', [TranslationController::class, 'index'])
    ->name('admin.languages.translations');
Route::get('/languages/{language}/translations/get-data', [TranslationController::class, 'getDataCollection'])
    ->name('admin.languages.translations.get-data');
Route::put('/languages/{language}/translations', [TranslationController::class, 'update'])
    ->name('admin.languages.translations.update');

Route::get('/settings/general', [SettingController::class, 'index']);

Route::put('/settings', [SettingController::class, 'update']);

Route::get('/settings/social-login', [SettingController::class, 'socialLogin'])
    ->name('admin.settings.social-login');
