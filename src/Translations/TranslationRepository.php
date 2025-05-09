<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Translations;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Juzaweb\Core\Models\Translation;
use Juzaweb\Core\Translations\Contracts\Translation as TranslationContract;
use RuntimeException;

class TranslationRepository implements TranslationContract
{
    protected array $modules = [];

    public function register(string $module, array $options = []): void
    {
        $this->modules[$module] = $options;
    }

    public function locale(string $module): LocaleRepository
    {
        $module = $this->find($module);

        return $this->createTranslationLocale(collect($module));
    }

    public function find(string $module): array
    {
        if ($module = Arr::get($this->modules, $module)) {
            return $module;
        }

        throw new RuntimeException('Module not found');
    }

    public function modules(): Collection
    {
        return collect($this->modules);
    }

    public function importTranslationLine(array $data, bool $force = false): Translation
    {
        if ($force) {
            return Translation::updateOrCreate(
                Arr::only($data, ['locale', 'group', 'namespace', 'key', 'object_type', 'object_key']),
                Arr::only($data, ['value'])
            );
        }

        return Translation::firstOrCreate(
            Arr::only($data, ['locale', 'group', 'namespace', 'key', 'object_type', 'object_key']),
            Arr::only($data, ['value'])
        );
    }

    protected function createTranslationLocale(Collection $module): LocaleRepository
    {
        return new LocaleRepository($module);
    }
}
