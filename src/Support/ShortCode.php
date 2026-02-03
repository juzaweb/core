<?php

namespace Juzaweb\Modules\Core\Support;

use Illuminate\Support\Str;
use Juzaweb\Modules\Core\Contracts\ShortCode as ShortCodeContract;

class ShortCode implements ShortCodeContract
{
    protected array $shortcodes = [];

    public function register(string $tag, callable|string $callback): void
    {
        $this->shortcodes[$tag] = $callback;
    }

    public function compile(string $content): string
    {
        $pattern = '/\[([\w\-_]+)([^\]]*)\](?:(.+?)\[\/\1\])?/s';

        return preg_replace_callback(
            $pattern,
            function ($matches) {
                $tag = $matches[1];
                $attr = $this->parseAttributes($matches[2]);
                $content = $matches[3] ?? null;

                if (isset($this->shortcodes[$tag])) {
                    return call_user_func($this->shortcodes[$tag], $attr, $content, $tag);
                }

                return $matches[0];
            },
            $content
        );
    }

    protected function parseAttributes($text): array
    {
        $attributes = [];
        $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';

        if (preg_match_all($pattern, $text, $match, PREG_SET_ORDER)) {
            foreach ($match as $m) {
                if (!empty($m[1])) {
                    $attributes[$m[1]] = stripcslashes($m[2]);
                } elseif (!empty($m[3])) {
                    $attributes[$m[3]] = stripcslashes($m[4]);
                } elseif (!empty($m[5])) {
                    $attributes[$m[5]] = stripcslashes($m[6]);
                } elseif (isset($m[7]) && strlen($m[7])) {
                    $attributes[] = stripcslashes($m[7]);
                } elseif (isset($m[8])) {
                    $attributes[] = stripcslashes($m[8]);
                }
            }
        }

        return $attributes;
    }
}
