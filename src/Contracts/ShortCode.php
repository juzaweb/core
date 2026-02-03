<?php

namespace Juzaweb\Modules\Core\Contracts;

interface ShortCode
{
    /**
     * Register a shortcode.
     *
     * @param string $tag
     * @param callable|string $callback
     * @return void
     */
    public function register(string $tag, callable|string $callback): void;

    /**
     * Compile shortcodes in content.
     *
     * @param string $content
     * @return string
     */
    public function compile(string $content): string;
}
