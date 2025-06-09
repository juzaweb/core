<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Themes\Commands;

use Juzaweb\TemplateDownloader\Commands\DownloadTemplateCommandAbstract;

class DownloadStyleCommand extends DownloadTemplateCommandAbstract
{
    protected $name = 'theme:download-style';

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
