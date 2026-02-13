<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

use Illuminate\Support\Facades\Route;
use Juzaweb\Modules\Admin\Http\Controllers\DashboardController;
use Juzaweb\Modules\Core\Http\Controllers\AddonController;
use Juzaweb\Modules\Core\Http\Controllers\Admin\ChartController;
use Juzaweb\Modules\Core\Http\Controllers\Admin\LanguageController;
use Juzaweb\Modules\Core\Http\Controllers\Admin\LoadDataController;
use Juzaweb\Modules\Core\Http\Controllers\Admin\ModuleController;
use Juzaweb\Modules\Core\Http\Controllers\Admin\PageController;
use Juzaweb\Modules\Core\Http\Controllers\Admin\ProfileController;
use Juzaweb\Modules\Core\Http\Controllers\Admin\RoleController;
use Juzaweb\Modules\Core\Http\Controllers\Admin\SettingController;
use Juzaweb\Modules\Core\Http\Controllers\Admin\SetupController;
use Juzaweb\Modules\Core\Http\Controllers\Admin\TranslationController;
use Juzaweb\Modules\Core\Http\Controllers\Admin\UserController;

require __DIR__ . '/components/media.php';
require __DIR__ . '/components/theme.php';

Route::get('/', [DashboardController::class, 'index'])
    ->name('admin.dashboard')
    ->permission('dashboard.index');
Route::get('/setup', [SetupController::class, 'index'])->name('admin.setup');
Route::post('/setup', [SetupController::class, 'setup'])->name('admin.setup.process');
Route::get('/dashboard/online', [DashboardController::class, 'online'])
    ->name('admin.dashboard.online-count');
Route::post('/remove-message', [AddonController::class, 'removeMessage'])
    ->name('admin.dashboard.remove-message');

Route::get('load-data', [LoadDataController::class, 'load'])
    ->name('admin.load-data');
Route::get('load-box', [LoadDataController::class, 'loadForMenu'])
    ->name('admin.load-box');

Route::get('charts/{chart}', [ChartController::class, 'chart'])
    ->name('admin.charts.data');

Route::get('/profile', [ProfileController::class, 'index'])->name('admin.profile');
Route::get('/profile/notifications', [ProfileController::class, 'notification'])
    ->name('admin.profile.notification');
Route::post('/profile', [ProfileController::class, 'update']);

Route::admin('languages', LanguageController::class)
    ->except(['edit', 'update', 'create']);
Route::admin('pages', PageController::class);
Route::admin('users', UserController::class);
Route::admin('roles', RoleController::class);

Route::get('modules', [ModuleController::class, 'index'])
    ->name('admin.modules.index')
    ->permission('modules.index');
Route::post('modules/toggle', [ModuleController::class, 'toggle'])
    ->name('admin.modules.toggle')
    ->permission('modules.edit');

Route::get('modules/marketplace', [ModuleController::class, 'marketplace'])
    ->name('admin.modules.marketplace')
    ->permission('modules.index');

Route::get('modules/marketplace/get-data', [ModuleController::class, 'loadMarketplaceData'])
    ->name('admin.modules.marketplace.get-data')
    ->permission('modules.index');

Route::get('/languages/{language}/translations', [TranslationController::class, 'index'])
    ->name('admin.languages.translations')
    ->permission('languages.index');
Route::get('/languages/{language}/translations/get-data', [TranslationController::class, 'getDataCollection'])
    ->name('admin.languages.translations.get-data')
    ->permission('languages.index');
Route::put('/languages/{language}/translations', [TranslationController::class, 'update'])
    ->name('admin.languages.translations.update')
    ->permission(['languages.edit']);

Route::post('translations/translate-model', [TranslationController::class, 'translateModel'])
    ->name('admin.translations.translate-model');
Route::post('translations/translate-status', [TranslationController::class, 'translateStatus'])->name('admin.translations.translate-status');

Route::get('/settings/general', [SettingController::class, 'index'])
    ->name('admin.settings.general')
    ->permission('settings.general.edit');

Route::put('/settings', [SettingController::class, 'update'])
    ->name('admin.settings.update')
    ->permission('settings.general.edit');

Route::get('/settings/social-login', [SettingController::class, 'socialLogin'])
    ->name('admin.settings.social-login')
    ->permission('settings.social-login.index');

Route::get('/settings/email', [SettingController::class, 'email'])
    ->name('admin.settings.email')
    ->permission('settings.email.index');
Route::post('/settings/test-email', [SettingController::class, 'testEmail'])
    ->name('admin.settings.test-email')
    ->permission('settings.email.index')
    ->middleware(['throttle:5,1']);
