<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package juzawebcms/juzawebcms
 * @author The Anh Dang
 *
 * Developed based on Laravel Framework
 * Github: https://github.com/juzawebcms/juzawebcms
*/

$adminPrefix = config('juzaweb.admin_prefix');

Route::group([
    'prefix' => $adminPrefix,
    'middleware' => 'guest'
], function () {
    Route::get('/login', 'Auth\LoginController@index')->name('admin.login');
    Route::post('/login', 'Auth\LoginController@login');

    Route::get('/register', 'Auth\RegisterController@index')->name('admin.register');
    Route::post('/register', 'Auth\RegisterController@register');

    Route::get('/forgot-password', 'Auth\ForgotPasswordController@index')->name('admin.forgot_password');
    Route::post('/forgot-password', 'Auth\ForgotPasswordController@forgotPassword');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/logout', 'Auth\LoginController@logout')->name('auth.logout');
});
