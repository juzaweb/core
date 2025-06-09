<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Juzaweb\Core\Mail\Test;
use Symfony\Component\Console\Input\InputOption;

class TestMailCommand extends Command
{
    protected $name = 'mail:test';

    public function handle(): int
    {
        $email = $this->option('email');

        if (empty($email)) {
            $this->error('Email is required');
            return self::FAILURE;
        }

        Mail::to($email)->send(new Test());

        $this->info('Mail sent successfully, check your inbox.');

        return self::SUCCESS;
    }

    protected function getOptions(): array
    {
        return [
            ['email', false, InputOption::VALUE_OPTIONAL, 'Email to send.'],
        ];
    }
}
