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
 * Date: 6/13/2021
 * Time: 12:50 PM
 */

Route::group([
    'prefix' => 'install',
    'namespace' => 'Juzaweb\Core\Http\Controllers',
    'middleware' => ['web', 'install']
], function () {
    Route::get('/', 'Installer\WelcomeController@welcome')->name('installer.welcome');
    Route::get('/environment', 'Installer\EnvironmentController@environment')->name('installer.environment');

    Route::post('/environment', 'Installer\EnvironmentController@save')->name('installer.environment.save');

    Route::get('/requirements', 'Installer\RequirementsController@requirements')->name('installer.requirements');

    Route::get('/permissions', 'Installer\PermissionsController@permissions')->name('installer.permissions');

    Route::get('/database', 'Installer\DatabaseController@database')->name('installer.database');

    Route::get('/admin', 'Installer\AdminController@index')->name('installer.admin');

    Route::post('/admin', 'Installer\AdminController@save')->name('installer.admin.save');

    Route::get('final', 'Installer\FinalController@finish')->name('installer.final');
});