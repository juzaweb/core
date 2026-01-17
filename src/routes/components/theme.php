<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

use Juzaweb\Modules\Core\Facades\RouteResource;
use Juzaweb\Modules\Core\Http\Controllers\Admin\MenuController;
use Juzaweb\Modules\Core\Http\Controllers\Admin\ThemeController;
use Juzaweb\Modules\Core\Http\Controllers\Admin\WidgetController;

Route::get('themes', [ThemeController::class, 'index'])
    ->name('admin.themes.index')
    ->middleware(['permission:themes.index']);
Route::get('themes/get-data', [ThemeController::class, 'loadData'])
    ->name('admin.themes.get-data')
    ->middleware(['permission:themes.index']);

Route::post('themes/activate', [ThemeController::class, 'activate'])
    ->name('admin.themes.activate')
    ->middleware(['permission:themes.edit']);

Route::group(
    ['prefix' => 'widgets'],
    function () {
        Route::get('/', [WidgetController::class, 'index'])->name('admin.widgets.index');
        Route::put('/{key}', [WidgetController::class, 'update'])->name('admin.widgets.update');
    }
);

RouteResource::admin('menus', MenuController::class)
    ->except(['create', 'edit'])
    ->name('admin.menus');
Route::get('menus/{id}', [MenuController::class, 'index'])
    ->name('admin.menus.show');
Route::delete('menus/{id}', [MenuController::class, 'destroy'])
    ->name('admin.menus.destroy');
