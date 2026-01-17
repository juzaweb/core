<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Contracts;

/**
 * @see \Juzaweb\Modules\Core\Themes\ThemeRepository
 */
interface Theme
{
    public function find(string $name): ?\Juzaweb\Modules\Core\Themes\Theme;

    public function current(): \Juzaweb\Modules\Core\Themes\Theme;

    public function init(): void;
}
