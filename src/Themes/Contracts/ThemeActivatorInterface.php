<?php

namespace Juzaweb\Modules\Core\Themes\Contracts;

use Juzaweb\Modules\Core\Themes\Theme;

interface ThemeActivatorInterface
{
    /**
     * Activates a theme
     */
    public function activate(Theme $theme): void;

    /**
     * Determine whether the given theme is active.
     */
    public function isActive(Theme $theme): bool;

    /**
     * Set active theme.
     */
    public function setActive(Theme $theme): void;

    /**
     * Sets active theme by its name
     */
    public function setActiveByName(string $name): void;

    /**
     * Get current active theme name
     */
    public function getActiveName(): ?string;

    /**
     * Deletes theme activation status
     */
    public function delete(Theme $theme): void;

    /**
     * Deletes any theme activation statuses created by this class.
     */
    public function reset(): void;
}
