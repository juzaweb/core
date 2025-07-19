<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Juzaweb\Core\Contracts\Sitemapable;
use Juzaweb\Core\Models\Language;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapGenerateCommand extends Command
{
    protected $name = 'sitemap:generate';

    protected $description = 'Generate site map';

    public function handle(): int
    {
        $locales = Language::languages()->keys();
        $defaultLocale = config('app.locale');

        $sitemap = Sitemap::create()->add(Url::create('/')->setPriority(1));

        if (count($locales) > 1) {
            // Add home page with locale
            foreach ($locales as $locale) {
                if ($locale === $defaultLocale) {
                    continue;
                }

                $sitemap->add(Url::create("/{$locale}")->setPriority(1));
            }
        }

        $models = [
            PostTranslation::class,
            ServiceTranslation::class,
        ];

        foreach ($models as $model) {
            /** @var Sitemapable $model */
            $path = 'sitemap/pages/' . $model::getSitemapPage() . '.xml';
            $latest = $model::latest('updated_at')->first(['updated_at'])->updated_at;

            if (! is_dir(public_path(dirname($path)))) {
                File::makeDirectory(public_path(dirname($path)), 0755, true);
            }

            Sitemap::create()
                ->add($model::forSitemap()->get())
                ->writeToFile(public_path($path));

            $sitemap->add(Url::create($path)->setLastModificationDate($latest));
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Site map generated successfully.');

        return 0;
    }
}
