<?php

namespace Juzaweb\Core\Manager;

use Throwable;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;

class FinalInstallManager
{
    /**
     * Run final commands.
     *
     * @return string
     * @throws Throwable
     */
    public function runFinal()
    {
        $outputLog = new BufferedOutput;

        $this->generateKey($outputLog);
        $this->publishStorage($outputLog);
        $this->publishVendorAssets($outputLog);

        return $outputLog->fetch();
    }

    /**
     * Generate New Application Key.
     *
     * @param \Symfony\Component\Console\Output\BufferedOutput $outputLog
     * @return \Symfony\Component\Console\Output\BufferedOutput|array
     */
    private static function generateKey(BufferedOutput $outputLog)
    {
        try {
            if (config('installer.final.key')) {
                Artisan::call('key:generate', ['--force'=> true], $outputLog);
            }

        } catch (Throwable $e) {
            return static::response($e->getMessage(), $outputLog);
        }

        return $outputLog;
    }

    private static function publishStorage(BufferedOutput $outputLog)
    {
        try {
            Artisan::call('storage:link', [], $outputLog);

        } catch (Throwable $e) {
            return static::response($e->getMessage(), $outputLog);
        }

        return $outputLog;
    }

    /**
     * Publish vendor assets.
     *
     * @param \Symfony\Component\Console\Output\BufferedOutput $outputLog
     * @return \Symfony\Component\Console\Output\BufferedOutput|array
     * @throws Throwable
     */
    private static function publishVendorAssets(BufferedOutput $outputLog)
    {
        try {
            Artisan::call('vendor:publish', [
                '--tag' => 'juzaweb_assets',
                '--force' => true
            ], $outputLog);

            Artisan::call('storage:link', [], $outputLog);

        } catch (Throwable $e) {
            throw $e;
        }

        return $outputLog;
    }

    /**
     * Return a formatted error messages.
     *
     * @param $message
     * @param \Symfony\Component\Console\Output\BufferedOutput $outputLog
     * @return array
     */
    private static function response($message, BufferedOutput $outputLog)
    {
        return [
            'status' => 'error',
            'message' => $message,
            'dbOutputLog' => $outputLog->fetch(),
        ];
    }
}
