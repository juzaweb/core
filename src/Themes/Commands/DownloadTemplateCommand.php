<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Themes\Commands;

use Juzaweb\TemplateDownloader\Commands\DownloadTemplateCommandAbstract;

class DownloadTemplateCommand extends DownloadTemplateCommandAbstract
{
    protected $name = 'theme:download-template';

    protected array $data = [];

    public function handle()
    {
        $this->data['name'] = $this->ask(
            'Theme Name?',
            $this->getDataDefault('name')
        );

        $this->setDataDefault('name', $this->data['name']);
    }
}
