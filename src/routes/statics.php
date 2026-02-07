<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

use Juzaweb\Modules\Core\Http\Controllers\AddonController;

if (config('filesystems.disks.cloud.stream_route', false)) {
    // If cloud disk is configured, register route to serve media from cloud storage
    $cloudDomain = parse_url(config('filesystems.disks.cloud.url'), PHP_URL_HOST);

    Route::get('/media/{path}', [AddonController::class, 'showFromCloud'])
        ->where('path', '.*')
        ->name('media.cloud.show');
}

Route::get('storage/{path}', [AddonController::class, 'storageProxy'])
    ->name('storage.proxy')
    ->where('path', '.*');

Route::get('juzaweb/{path}', [AddonController::class, 'juzawebProxy'])
    ->name('juzaweb.proxy')
    ->where('path', '.*');

Route::get('vendor/{path}', [AddonController::class, 'vendorProxy'])
    ->name('vendor.proxy')
    ->where('path', '.*');

Route::get('themes/{theme}/{path}', [AddonController::class, 'themesProxy'])
    ->name('themes.proxy')
    ->where('path', '.*');

Route::get('modules/{module}/{path}', [AddonController::class, 'modulesProxy'])
    ->name('modules.proxy')
    ->where('path', '.*');

Route::get('images/{method}/{hash}/{filename}', [AddonController::class, 'proxy'])
    ->name('imgproxy.handle');
