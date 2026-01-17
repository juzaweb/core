<?php

namespace Juzaweb\Modules\Core\FileManager;

use Juzaweb\Modules\Core\FileManager\Contracts\ImageConversion;
use Juzaweb\Modules\Core\FileManager\Exceptions\InvalidConversion;

class ImageConversionRepository implements ImageConversion
{
    /** @var array */
    protected array $conversions = [];

    /** @var array */
    protected array $globalConversions = [];

    /**
     * Get all the registered conversions.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->conversions;
    }

    /**
     * Register a new conversion.
     *
     * @param string $name
     * @param callable $conversion
     * @return void
     */
    public function register(string $name, callable $conversion): void
    {
        $this->conversions[$name] = $conversion;
    }

    /**
     * Get the conversion with the specified name.
     *
     * @param  string  $name
     * @return callable
     * @throws InvalidConversion
     */
    public function get(string $name): callable
    {
        if (!$this->exists($name)) {
            throw InvalidConversion::doesNotExist($name);
        }

        return $this->conversions[$name];
    }

    /**
     * Determine if a conversion with the specified name exists.
     *
     * @param string $name
     * @return bool
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
     * @return void
     */
    public function pushGlobalConversions(...$conversions): void
    {
        $this->globalConversions = array_merge($this->globalConversions, $conversions);
    }

    /**
     * Get the global conversions.
     *
     * @return array
     */
    public function getGlobalConversions(): array
    {
        return $this->globalConversions;
    }
}
