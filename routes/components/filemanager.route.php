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
 * Date: 6/19/2021
 * Time: 7:46 PM
 */

Route::group(['prefix' => 'file-manager'], function () {
    Route::get('/', 'FileManager\FilemanagerController@index');

    Route::get('/errors', 'FileManager\FilemanagerController@getErrors');

    Route::any('/upload', 'FileManager\UploadController@upload')->name('filemanager.upload');

    Route::get('/jsonitems', 'FileManager\ItemsController@getItems');

    /*Route::get('/move', 'ItemsController@move');

    Route::get('/domove', 'ItemsController@domove');*/

    Route::post('/newfolder', 'FileManager\FolderController@addfolder');

    Route::get('/folders', 'FileManager\FolderController@getFolders');

    /*Route::get('/rename', 'RenameController@getRename');

    Route::get('/download', 'DownloadController@getDownload');*/

    Route::post('/delete', 'FileManager\DeleteController@delete');
});
