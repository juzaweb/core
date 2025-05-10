<?php
/**
 * @package    larabizcom/larabiz
 * @author     The Anh Dang
 * @link       https://github.com/juzaweb/cms
 * @license    GNU V2
 */

namespace Juzaweb\Core\Providers;

use Illuminate\Support\Facades\Blade;
use Juzaweb\Core\Contracts\Hook;
use Juzaweb\Core\Hooks\HookRepository;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        /*
         * Adds a directive in Blade for actions
         */
        Blade::directive(
            'do_action',
            function ($expression) {
                return "<?php app(Hook::class)->action({$expression}); ?>";
            }
        );

        /*
         * Adds a directive in Blade for filters
         */
        Blade::directive(
            'apply_filters',
            function ($expression) {
                return "<?php echo app(Hook::class)->filter({$expression}); ?>";
            }
        );
    }

    public function register(): void
    {
        // Registers the eventy singleton.
        $this->app->singleton(
            Hook::class,
            function () {
                return new HookRepository();
            }
        );
    }
}
