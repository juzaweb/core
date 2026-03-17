<?php

namespace Juzaweb\Modules\Core\FileManager;

use Juzaweb\Modules\Core\FileManager\Contracts\ImageConversion;
use Juzaweb\Modules\Core\FileManager\Exceptions\InvalidConversion;

class ImageConversionRepository implements ImageConversion
{
    protected array $conversions = [];

    protected array $globalConversions = [];

    /**
     * Get all the registered conversions.
     */
    public function all(): array
    {
        return $this->conversions;
    }

    /**
     * Register a new conversion.
     */
    public function register(string $name, callable $conversion): void
    {
        $this->conversions[$name] = $conversion;
    }

    /**
     * Get the conversion with the specified name.
     *
     * @throws InvalidConversion
     */
    public function get(string $name): callable
    {
        if (! $this->exists($name)) {
            throw InvalidConversion::doesNotExist($name);
        }

        return $this->conversions[$name];
    }

    /**
     * Determine if a conversion with the specified name exists.
     */
    public function exists(string $name): bool
    {
        return isset($this->conversions[$name]);
    }

    /**
     * Get the global conversions.
     *
     * @return array
     */
    public function globalConversions(array $globalConversions): void
    {
        $this->globalConversions = $globalConversions;
    }

    /**
     * Push the global conversions.
     *
     * @param  mixed  ...$conversions
     */
    public function pushGlobalConversions(...$conversions): void
    {
        $this->globalConversions = array_merge($this->globalConversions, $conversions);
    }

    /**
     * Get the global conversions.
     */
    public function getGlobalConversions(): array
    {
        return $this->globalConversions;
    }
}
