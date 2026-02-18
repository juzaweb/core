<?php

namespace Juzaweb\Modules\Core\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Img extends Component
{
    public function __construct(
        public string $src,
        public string $alt = '',
        public string $class = '',
        public ?int $width = null,
        public ?int $height = null,
        public bool|string $thumbnail = false,
        public bool $crop = false,
    ) {
    }

    public function render(): View
    {
        return view('core::components.img');
    }

    public function getSrc(): string
    {
        return proxy_image($this->src, $this->width, $this->height, $this->crop);
    }

    public function getPlaceHolder(): string
    {
        if ($this->thumbnail) {
            return proxy_image($this->src, 50, 50, true);
        }

        return "data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==";
    }
}
