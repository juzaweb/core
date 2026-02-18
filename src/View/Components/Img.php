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
        public array $srcset = [],
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
            return proxy_image($this->src, 50, null, false);
        }

        return "data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==";
    }

    public function getSrcset(): string
    {
        if (empty($this->srcset)) {
            return '';
        }

        $paths = [];

        foreach ($this->srcset as $width => $current_dimensions) {
            // $current_dimensions can be [width, height] or just width
            if (is_array($current_dimensions)) {
                $w = $current_dimensions[0];
                $h = $current_dimensions[1] ?? null;
            } else {
                $w = $current_dimensions;
                $h = null; // Auto height based on aspect ratio if proxy_image supports it, or just resize by width
            }

            // Using width as key (e.g. '320w') if array key is string, otherwise append 'w' to width
            $descriptor = is_string($width) ? $width : "{$w}w";

            $url = proxy_image($this->src, $w, $h, $this->crop);

            $paths[] = "{$url} {$descriptor}";
        }

        return implode(', ', $paths);
    }
}
