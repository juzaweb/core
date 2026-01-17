<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

use Juzaweb\Modules\Core\FileManager\Http\Controllers\UploadController;
use Juzaweb\Modules\Core\Http\Controllers\Admin\MediaController;

Route::get('media', [MediaController::class, 'index'])
    ->name('admin.media.index')
    ->middleware(['permission:media.index']);
Route::get(
    'media/folder/{folder}',
    [MediaController::class, 'index']
)
    ->name('admin.media.folder')
    ->middleware(['permission:media.index']);
Route::get('media/load-more', [MediaController::class, 'loadMore'])
    ->name('admin.media.load-more')
    ->middleware(['permission:media.index']);
Route::get('media/folder/{folder}/load-more', [MediaController::class, 'loadMore'])
    ->name('admin.media.folder.load-more')
    ->middleware(['permission:media.index']);
Route::get('media/download/{disk}/{id}', [MediaController::class, 'download'])
    ->name('admin.media.download')
    ->middleware(['permission:media.index']);

Route::put('media/{id}', [MediaController::class, 'update'])
    ->name('admin.media.update')
    ->middleware(['permission:media.edit']);

Route::delete('media/{id}', [MediaController::class, 'destroy'])
    ->name('admin.media.delete')
    ->middleware(['permission:media.delete']);

Route::post('media/folders', [MediaController::class, 'addFolder'])
    ->name('admin.media.folders.store')
    ->middleware(['permission:media.folder.create']);

Route::group(
    [
        'prefix' => 'file-manager',
    ],
    function () {
        //Route::post('/{disk}/browser/delete', [BrowserController::class, 'delete']);

        Route::post('/{disk}/upload', [UploadController::class, 'upload'])
            ->name('media.upload');

        Route::post('/{disk}/import', [UploadController::class, 'import'])
            ->name('media.import');
    }
);
