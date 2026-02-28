<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Translations;

use Illuminate\Support\ServiceProvider;
use Juzaweb\Modules\Core\Translations\Commands\ConvertConfigCountryCommand;
use Juzaweb\Modules\Core\Translations\Commands\ExportTranslationCommand;
use Juzaweb\Modules\Core\Translations\Commands\ImportTranslationCommand;
use Juzaweb\Modules\Core\Translations\Commands\MakeLanguageCommand;
use Juzaweb\Modules\Core\Translations\Commands\ModelTranslateCommand;
use Juzaweb\Modules\Core\Translations\Commands\TranslateCommand;
use Juzaweb\Modules\Core\Translations\Commands\TranslateViewTextCommand;
use Juzaweb\Modules\Core\Translations\Contracts\IP2Location;
use Juzaweb\Modules\Core\Translations\Contracts\Translation;
use Juzaweb\Modules\Core\Translations\Contracts\TranslationFinder as TranslationFinderContract;
use Juzaweb\Modules\Core\Translations\Contracts\Translator;
use Juzaweb\Modules\Core\Translations\Translation as TranslationModel;

class TranslationsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['config']->set('translation-loader.model', TranslationModel::languageLineModel());

        $this->commands(
            [
                MakeLanguageCommand::class,
                ExportTranslationCommand::class,
                ImportTranslationCommand::class,
                TranslateCommand::class,
                ModelTranslateCommand::class,
                ConvertConfigCountryCommand::class,
                TranslateViewTextCommand::class,
            ]
        );

        $this->app[Translation::class]->register(
            "laravel",
            [
                'type' => 'laravel',
                'key' => 'laravel',
                'namespace' => '*',
                'lang_path' => resource_path('lang'),
                'src_path' => app_path(),
                'publish_path' => resource_path('lang'),
            ]
        );

        $this->app->singleton('translatable.locales', Locales::class);
        $this->app->singleton(\Astrotomic\Translatable\Locales::class, Locales::class);
    }

    public function register(): void
    {
        $this->app->singleton(
            Translation::class,
            function ($app) {
                return new TranslationRepository();
            }
        );

        $this->app->singleton(
            TranslationFinderContract::class,
            function ($app) {
                return new TranslationFinder(
                    $app[Translation::class]
                );
            }
        );

        $this->app->bind(
            Translator::class,
            function ($app) {
                $driver = $app['config']->get('translator.driver', 'ex-google');

                return new ($app['config']->get("translator.drivers.{$driver}")['class']);
            }
        );

        $this->app->singleton(
            IP2Location::class,
            function ($app) {
                $dataPath = base_path('database/iplocation/IPV6-COUNTRY.BIN');
                return new IP2LocationFactory($dataPath);
            }
        );
    }
}
