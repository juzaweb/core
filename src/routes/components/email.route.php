<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzawebcms/juzawebcms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://github.com/juzawebcms/juzawebcms
 * @license    MIT
 *
 * Created by JUZAWEB.
 * Date: 6/23/2021
 * Time: 10:19 AM
 */

Route::group(['prefix' => 'setting/email'], function () {
    Route::get('/', 'Backend\Email\EmailController@index')->name('admin.setting.test-email');

    Route::post('/', 'Backend\Email\EmailController@save')->name('admin.setting.email.save');

    Route::post('send-test-mail', 'Backend\Email\EmailController@sendTestMail')->name('admin.email.test-email');
});

Route::jwResource('email-template', 'Backend\Email\EmailTemplateController');

Route::group(['prefix' => 'logs/email'], function () {
    Route::get('/', 'Backend\Email\EmailLogsController@index')->name('admin.logs.email');

    Route::get('/get-data', 'Backend\Email\EmailLogsController@getData')->name('admin.logs.email.getdata');

    Route::post('/status', 'Backend\Email\EmailLogsController@status')->name('admin.logs.email.status');

    Route::post('/remove', 'Backend\Email\EmailLogsController@remove')->name('admin.logs.email.remove');
});
