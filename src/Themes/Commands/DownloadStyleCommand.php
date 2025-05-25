<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Themes\Commands;

use Juzaweb\TemplateDownloader\Commands\DownloadTemplateCommandAbstract;

class DownloadStyleCommand extends DownloadTemplateCommandAbstract
{
    protected $signature = 'theme:download-style';

    protected array $data;

    public function handle(): void
    {
        $this->data['name'] = $this->ask(
            'Theme Name?',
            $this->getDataDefault('name')
        );

        $this->setDataDefault('name', $this->data['name']);
    }
}
