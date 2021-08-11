<?php
/**
 * MYMO CMS - Free Laravel CMS
 *
 * @package    juzawebcms/juzawebcms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://github.com/juzawebcms/juzawebcms
 * @license    MIT
 *
 * Created by The Anh.
 * Date: 5/29/2021
 * Time: 2:24 PM
 */

Route::group(['prefix' => 'plugins'], function () {
    Route::get('/', 'Backend\ModuleController@index')->name('admin.module');

    Route::get('/get-data', 'Backend\ModuleController@getDataTable')->name('admin.module.get-data');

    Route::post('/bulk-actions', 'Backend\ModuleController@bulkActions')->name('admin.module.bulk-actions');
});
