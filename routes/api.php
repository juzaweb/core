<?php
/**
 * MYMO CMS - API Routes
 *
 * @package juzawebcms/juzawebcms
 * @author The Anh Dang
 *
 * Developed based on Laravel Framework
 * Github: https://github.com/juzawebcms/juzawebcms
 */

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'Api\Auth\LoginController@login');

    Route::post('refresh', 'Api\Auth\LoginController@refresh');

    Route::post('logout', 'Api\Auth\LoginController@logout');
    //Route::post('profile', 'Auth\LoginController@profile');
});
