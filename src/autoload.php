<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/laravel-cms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

$loader = require JW_BASEPATH . '/vendor/autoload.php';

$pluginFile = JW_BASEPATH . '/bootstrap/cache/plugins_statuses.php';
if (file_exists($pluginFile)) {
    $plugins = require $pluginFile;
    $pluginsFolder = JW_BASEPATH . '/plugins';

    foreach ($plugins as $pluginInfo) {
        foreach ($pluginInfo as $key => $item) {
            $path = $pluginsFolder . '/' . $item['path'];
            $namespace = $item['namespace'] ?? '';

            if (is_dir($path) && $namespace) {
                $loader->setPsr4($namespace, [$path]);
            }
        }
    }
}

