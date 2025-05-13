<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://github.com/juzaweb/cms
 * @license    GNU V2
 */

use Juzaweb\Core\Http\Controllers\Media\FileManagerController;
use Juzaweb\Core\Http\Controllers\Media\UploadController;
use Juzaweb\Core\Http\Controllers\Media\ItemsController;
use Juzaweb\Core\Http\Controllers\Media\FolderController;
use Juzaweb\Core\Http\Controllers\Media\DeleteController;

Route::group(
    ['prefix' => 'media/browser'],
    function () {
        Route::get('/', [FileManagerController::class, 'index']);

        Route::get('/errors', [FileManagerController::class, 'getErrors']);

        Route::any('/upload', [UploadController::class, 'upload'])->name('media.upload');

        Route::any('/import', [UploadController::class, 'import'])->name('media.import');

        Route::get('/items', [ItemsController::class, 'getItems']);

        Route::post('/newfolder', [FolderController::class, 'addfolder']);

        Route::get('/folders', [FolderController::class, 'getFolders']);

        Route::post('/delete', [DeleteController::class, 'delete']);
    }
);
