<?php

namespace Juzaweb\Core\Manager;

use Juzaweb\Core\Helpers\Installer;

class InstalledFileManager
{
    /**
     * Create installed file.
     *
     * @return int
     */
    public function create()
    {
        $installedLogFile = Installer::installedPath();
        $dateStamp = date('Y/m/d h:i:sa');

        if (!file_exists($installedLogFile)) {
            $message = trans('juzaweb::installer.message.installed.success_log_message').$dateStamp."\n";

            file_put_contents($installedLogFile, $message);
        } else {
            $message = trans('juzaweb::installer.message.updater.log.success_message').$dateStamp;

            file_put_contents($installedLogFile, $message.PHP_EOL, FILE_APPEND | LOCK_EX);
        }

        return $message;
    }

    /**
     * Update installed file.
     *
     * @return int
     */
    public function update()
    {
        return $this->create();
    }
}
