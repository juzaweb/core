<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Logging;

use Illuminate\Http\Request;

class AddCustomInformation
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function __invoke($logger): void
    {
        // Skip in console
        if (app()->runningInConsole()) {
            return;
        }

        foreach ($logger->getHandlers() as $handler) {
            $handler->pushProcessor([$this, 'processLogRecord']);
        }
    }

    public function processLogRecord($record)
    {
        $user = $this->request->user();

        $record['extra'] += [
            'user_id' => $user->id ?? 'guest',
            'user_type' => $user ? get_class($user) : 'guest',
            'ip' => client_ip(),
            'url' => $this->request->fullUrl(),
        ];

        return $record;
    }
}
