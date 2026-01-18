<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Translations\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Juzaweb\Modules\Admin\Models\Website;
use Juzaweb\Modules\Admin\Networks\Facades\Network;
use Juzaweb\Modules\Core\Translations\Contracts\Translatable;

class ModelTranslateJob implements ShouldQueue
{
    use Queueable, Dispatchable;

    public int $timeout = 300; // 5 minutes

    public bool $failOnTimeout = true;

    public function __construct(
        protected Translatable $model,
        protected string $sourceLocale,
        protected string $targetLocale
    ) {
        $this->onQueue(config('translator.queue', 'default'));
    }

    public function handle(): void
    {
        $this->model->translateTo($this->targetLocale, $this->sourceLocale, ['force' => true]);

        sleep(1);
    }

    /**
     * Handle a job failure.
     *
     * @param Exception $exception
     * @return void
     */
    public function failed(Exception $exception): void
    {
        $translateHistory = $this->model->getTranslateHistory($this->targetLocale);

        $translateHistory?->markAsFailed($exception->getMessage());
    }
}
