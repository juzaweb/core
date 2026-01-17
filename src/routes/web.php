<?php

use Illuminate\Support\Facades\Route;
use Juzaweb\Modules\Core\Facades\Locale;
use Juzaweb\Modules\Core\Http\Controllers\Frontend\AddonController;
use Juzaweb\Modules\Core\Http\Controllers\Frontend\NotificationSubscribeController;
use Juzaweb\Modules\Core\Http\Controllers\Frontend\SitemapController;
use Juzaweb\Modules\Core\Http\Middleware\VerifyToken;

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
Route::post('verify/recaptcha', [AddonController::class, 'recaptcha'])
    ->name('addon.recaptcha');
Route::get('generator/thumbnail', [AddonController::class, 'thumbnail'])
    ->name('generate.thumbnail');

Route::post('notification/{channel}/subscribe', [NotificationSubscribeController::class, 'subscribe'])
    ->name('notification.subscribe')
    ->middleware(['throttle:5,1']);

Route::get('notification/{channel}/verify', [NotificationSubscribeController::class, 'verify'])
    ->name('notification.verify')
    ->middleware(['signed']);
