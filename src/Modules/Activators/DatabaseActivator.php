<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Modules\Activators;

use Illuminate\Container\Container;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Juzaweb\Modules\Core\Contracts\Setting;
use Juzaweb\Modules\Core\Modules\Contracts\ActivatorInterface;
use Juzaweb\Modules\Core\Modules\Exceptions\ModuleNotFoundException;
use Juzaweb\Modules\Core\Modules\Module;

class DatabaseActivator implements ActivatorInterface
{
    private Setting $dbConfig;

    public function __construct(Container $app)
    {
        $this->dbConfig = $app[Setting::class];
    }

    /**
     * Enables a plugin
     *
     * @param Module $module
     * @throws FileNotFoundException
     */
    public function enable(Module $module): void
    {
        $this->setActiveByName($module->getName(), true);
    }

    /**
     * Disables a plugin
     *
     * @param Module $module
     * @throws FileNotFoundException
     */
    public function disable(Module $module): void
    {
        $this->setActiveByName($module->getName(), false);
    }

    /**
     * Determine whether the given status same with a plugin status.
     *
     * @param Module $module
     * @param bool $status
     *
     * @return bool
     */
    public function hasStatus(Module $module, $status): bool
    {
        $modulesStatuses = $this->getModulesStatuses();
        if (! isset($modulesStatuses[$module->getName()])) {
            return $status === false;
        }

        return $status === true;
    }

    /**
     * Set active state for a plugin.
     *
     * @param Module $module
     * @param bool $active
     * @throws ModuleNotFoundException
     * @throws FileNotFoundException
     */
    public function setActive(Module $module, $active): void
    {
        $this->setActiveByName($module, $active);
    }

    /**
     * Sets a plugin status by its name
     *
     * @param string $module
     * @param bool $active
     */
    public function setActiveByName($module, $active): void
    {
        $modulesStatuses = $this->getModulesStatuses();
        if ($active) {
            $modulesStatuses[$module] = $module;
        } else {
            unset($modulesStatuses[$module]);
        }

        $this->writeData($modulesStatuses);
    }

    /**
     * Deletes a plugin activation status
     *
     * @param  Module $module
     */
    public function delete(Module $module): void
    {
        $modulesStatuses = $this->getModulesStatuses();
        unset($modulesStatuses[$module->getName()]);
        $this->writeData($modulesStatuses);
    }

    /**
     * Get plugin info load
     *
     * @param  Module $module
     * @return ?array
     */
    public function getAutoloadInfo(Module $module): ?array
    {
        return $this->modulesStatuses[$module->getName()] ?? [];
    }

    /**
     * Deletes any plugin activation statuses created by this class.
     */
    public function reset(): void
    {
        $this->writeData([]);
    }

    /**
     * Get plugins statuses, either from the cache or from
     * the json statuses file if the cache is disabled.
     * @return array
     */
    public function getModulesStatuses(): array
    {
        try {
            return $this->dbConfig->get('plugin_statuses', []);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Writes the activation statuses in a file, as json
     */
    private function writeData($modulesStatuses): void
    {
        setting()->set('plugin_statuses', $modulesStatuses);
    }
}
