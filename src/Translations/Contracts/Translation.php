<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Translations\Contracts;

use Illuminate\Support\Collection;
use Juzaweb\Core\Models\Translation as TranslationModel;

interface Translation
{
    public function modules(): Collection;

    public function find(string $module): array;

    public function register(string $module, array $options = []): void;

    public function importTranslationLine(array $data, bool $force = false): TranslationModel;
}
