<?php

namespace Juzaweb\Modules\Core\FileManager\Contracts;

use Juzaweb\Modules\Core\FileManager\ImageConversionRepository;

/**
 * @mixin ImageConversionRepository
 */
interface ImageConversion
{
    /**
     * Get all the registered conversions.
     */
    public function all(): array;

    /**
     * Register a new conversion.
     */
    public function register(string $name, callable $conversion): void;

    /**
     * Get the conversion with the specified name.
     */
    public function get(string $name): callable;

    /**
     * Determine if a conversion with the specified name exists.
     */
    public function exists(string $name): bool;

    /**
     * Get the global conversions.
     */
    public function getGlobalConversions(): array;
}
