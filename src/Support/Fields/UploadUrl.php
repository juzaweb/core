<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Support\Fields;

use Illuminate\Contracts\View\View;

class UploadUrl extends Field
{
    protected string $uploadType = 'image';

    protected string $disk = 'public';

    public function uploadType(string $type): static
    {
        $this->uploadType = $type;

        return $this;
    }

    public function disk(string $disk): static
    {
        $this->disk = $disk;

        return $this;
    }

    public function render(): View|string
    {
        return view(
            'core::fields.upload-url',
            [
                ...$this->renderParams(),
                'uploadType' => $this->uploadType,
                'disk' => $this->disk,
            ]
        );
    }
}
