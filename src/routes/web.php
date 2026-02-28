<?php

use Illuminate\Support\Facades\Route;
use Juzaweb\Modules\Core\Facades\Locale;
use Juzaweb\Modules\Core\FileManager\Http\Controllers\UploadController;
use Juzaweb\Modules\Core\Http\Controllers\AddonController;
use Juzaweb\Modules\Core\Http\Controllers\Frontend\SitemapController;

if (!Theme::current()) {
    Route::get('/', [AddonController::class, 'redirect']);
}

Route::group(['prefix' => Locale::setLocale()], function () {
    require __DIR__ . '/components/auth.php';
});


Route::get('sitemap.xml', [SitemapController::class, 'index'])
    ->name('sitemap.xml');

Route::get('sitemap/{page}.xml', [SitemapController::class, 'pages'])
    ->name('sitemap.pages')
    ->where('page', '[a-z0-9\-]+');

Route::get('sitemap/{provider}/page-{page}.xml', [SitemapController::class, 'provider'])
    ->name('sitemap.provider')
    ->where('provider', '[a-z0-9\-]+')
    ->where('page', '[0-9]+');

Route::post('online/statuses', [AddonController::class, 'statuses'])
    ->name('online.statuses');

Route::get('generator/thumbnail', [AddonController::class, 'thumbnail'])
    ->name('generate.thumbnail');

Route::group([
    'middleware' => [
        'auth',
        'verified',
    ]
], function() {
    Route::post('temp/upload', [UploadController::class, 'uploadTemp'])
        ->name('upload.temp');
});
