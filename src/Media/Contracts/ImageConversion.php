<?php

namespace Juzaweb\Core\Media\Contracts;

use Juzaweb\Core\Media\ImageConversionRepository;

/**
 * @mixin ImageConversionRepository
 */
interface ImageConversion
{
    /**
     * Get all the registered conversions.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Register a new conversion.
     *
     * @param string $name
     * @param callable $conversion
     * @return void
     */
    public function register(string $name, callable $conversion): void;

    /**
     * Get the conversion with the specified name.
     *
     * @param string $name
     * @return callable
     */
    public function get(string $name): callable;

    /**
     * Determine if a conversion with the specified name exists.
     *
     * @param string $name
     * @return bool
     */
    public function exists(string $name): bool;

    /**
     * Get the global conversions.
     *
     * @return array
     */
    public function getGlobalConversions(): array;
}
