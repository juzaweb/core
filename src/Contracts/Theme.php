<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Contracts;

use Illuminate\Support\Collection;

/**
 * @see \Juzaweb\Modules\Core\Themes\ThemeRepository
 */
interface Theme
{
    /**
     * Get all themes.
     *
     * @return Collection<\Juzaweb\Modules\Core\Themes\Theme>
     */
    public function all(): Collection;

    public function find(string $name): ?\Juzaweb\Modules\Core\Themes\Theme;

    public function findOrFail(string $name): \Juzaweb\Modules\Core\Themes\Theme;

    public function current(): ?\Juzaweb\Modules\Core\Themes\Theme;

    public function has(string $name): bool;

    public function activate(string $theme): bool;

    public function init(): void;
}
