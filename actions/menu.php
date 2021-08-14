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
 * Date: 5/26/2021
 * Time: 9:18 PM
*/

use Juzaweb\Core\Facades\HookAction;

HookAction::addAdminMenu(
    trans('juzaweb::app.dashboard'),
    'dashboard',
    [
        'icon' => 'fa fa-dashboard',
        'position' => 1
    ]
);

/*HookAction::addAdminMenu(
    'juzaweb::app.dashboard',
    'dashboard',
    [
        'icon' => 'fa fa-dashboard',
        'position' => 1,
        'parent' => 'dashboard',
    ]
);

HookAction::addAdminMenu(
    'juzaweb::app.updates',
    'updates',
    [
        'icon' => 'fa fa-refresh',
        'position' => 2,
        'parent' => 'dashboard',
    ]
);
*/

/*HookAction::addAdminMenu(
    trans('juzaweb::app.media'),
    'media',
    [
        'icon' => 'fa fa-image',
        'position' => 10
    ]
);*/

HookAction::addAdminMenu(
    trans('juzaweb::app.users'),
    'users',
    [
        'icon' => 'fa fa-users',
        'position' => 60
    ]
);

HookAction::addAdminMenu(
    trans('juzaweb::app.setting'),
    'setting',
    [
        'icon' => 'fa fa-cogs',
        'position' => 70
    ]
);

HookAction::addAdminMenu(
    trans('juzaweb::app.general_setting'),
    'setting.system',
    [
        'icon' => 'fa fa-cogs',
        'position' => 1,
        'parent' => 'setting',
    ]
);

/*HookAction::addAdminMenu(
    trans('juzaweb::app.translations'),
    'setting.language',
    [
        'icon' => 'fa fa-language',
        'position' => 5,
        'parent' => 'setting',
    ]
);*/






