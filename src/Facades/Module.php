<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Juzaweb\Modules\Core\Modules\Module[] all()
 * @method static array getCached()
 * @method static array scan()
 * @method static \Juzaweb\Modules\Core\Modules\Support\Collection toCollection()
 * @method static array getScanPaths()
 * @method static array allEnabled()
 * @method static array allDisabled()
 * @method static int count()
 * @method static array getOrdered($direction = 'asc')
 * @method static array getByStatus($status)
 * @method static \Juzaweb\Modules\Core\Modules\Module|null find(string $name)
 * @method static \Juzaweb\Modules\Core\Modules\Module findOrFail(string $name)
 * @method static string getModulePath($moduleName)
 * @method static \Illuminate\Filesystem\Filesystem getFiles()
 * @method static mixed config(string $key, $default = null)
 * @method static string getPath()
 * @method static void boot()
 * @method static void register()
 * @method static string assetPath(string $module)
 * @method static bool delete(string $module)
 * @method static bool isEnabled(string $name)
 * @method static bool isDisabled(string $name)
 * @method static void enable(string $name)
 * @method static void disable(string $name)
 * @method static void update(string $module)
 * @method static \Symfony\Component\Process\Process install($name, $version = 'dev-master', $type = 'composer', $subtree = false)
 * @method static string|null getStubPath()
 * @method static \Juzaweb\Modules\Core\Modules\FileRepository setStubPath(string $stubPath)
 * @method static void setUsed(string $name)
 * @method static void forgetUsed()
 * @method static string getUsedNow()
 * @method static string getAssetsPath()
 * @method static string asset(string $asset)
 * @see \Juzaweb\Modules\Core\Modules\FileRepository
 */
class Module extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Juzaweb\Modules\Core\Modules\Contracts\RepositoryInterface::class;
    }
}
