<?php

namespace Juzaweb\Core\Traits;

trait Whenable
{
    /**
     * Execute a callback if a condition is true, otherwise execute a default callback.
     *
     * @param mixed $condition The condition to evaluate.
     * @param callable $callback The callback to execute if the condition is true.
     * @param callable|null $default The callback to execute if the condition is false.
     * @return static
     */
    public function when($condition, callable $callback, ?callable $default = null): static
    {
        if ($condition) {
            $callback($this);
        } else {
            $default($this);
        }

        return $this;
    }
}
