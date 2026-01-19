<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @author     The Anh Dang
 *
 * @link       https://cms.juzaweb.com
 *
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Http\Controllers\Frontend;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Juzaweb\Modules\Core\Contracts\Sitemap as SitemapContract;
use Juzaweb\Modules\Core\Contracts\Sitemapable;
use Juzaweb\Modules\Core\Http\Controllers\Controller;
use Juzaweb\Modules\Core\Models\Pages\Page;
use Juzaweb\Modules\Core\Models\Pages\PageTranslation;
use Juzaweb\Modules\Core\Translations\Models\Language;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Sitemap as SitemapTag;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    protected const ITEMS_PER_PAGE = 500;

    protected const CACHE_TTL = 3600;

    public function __construct(protected SitemapContract $sitemapRepository)
    {
        //
    }

    /**
     * Generate and return sitemap index (main sitemap.xml)
     */
    public function index(): Response
    {
        $content = Cache::remember('sitemap.index', self::CACHE_TTL, function () {
            $sitemapIndex = SitemapIndex::create();

            // Add home page sitemap
            $homePage = Page::where('id', theme_setting('home_page'))->first();
            $homeUrl = SitemapTag::create(
                route('sitemap.pages', ['page' => 'home'])
            );
            if ($homePage && isset($homePage->updated_at)) {
                $homeUrl->setLastModificationDate($homePage->updated_at);
            }

            $sitemapIndex->add($homeUrl);

            // Get all registered sitemap providers
            $providers = $this->sitemapRepository->all();

            foreach ($providers as $key => $modelClass) {
                if (! class_exists($modelClass)) {
                    continue;
                }

                // Check if class implements Sitemapable interface
                if (! in_array(Sitemapable::class, class_implements($modelClass) ?: [])) {
                    continue;
                }

                /** @var Sitemapable $modelClass */
                try {
                    // Count total items for this provider
                    $totalItems = $modelClass::forSitemap()->count();

                    if ($totalItems === 0) {
                        continue;
                    }

                    // Calculate number of pages needed
                    $totalPages = (int) ceil($totalItems / self::ITEMS_PER_PAGE);

                    // Add sub-sitemap URL for each page
                    for ($page = 1; $page <= $totalPages; $page++) {
                        $latest = $modelClass::forSitemap()
                            ->skip(($page - 1) * self::ITEMS_PER_PAGE)
                            ->take(self::ITEMS_PER_PAGE)
                            ->latest('updated_at')
                            ->first(['updated_at']);

                        $url = SitemapTag::create(
                            route('sitemap.provider', [
                                'provider' => $key,
                                'page' => $page,
                            ])
                        );

                        if ($latest && isset($latest->updated_at)) {
                            $url->setLastModificationDate($latest->updated_at);
                        }

                        $sitemapIndex->add($url);
                    }
                } catch (\Exception $e) {
                    // Skip this provider if there's an error (e.g., table doesn't exist)
                    continue;
                }
            }

            return $sitemapIndex->render();
        });

        return response($content, 200, [
            'Content-Type' => 'text/xml',
        ]);
    }

    /**
     * Generate sitemap for a specific page (home, locales)
     */
    public function pages(string $page): Response
    {
        if ($page !== 'home') {
            abort(404, 'Sitemap page not found');
        }

        $content = Cache::remember("sitemap.pages.{$page}", self::CACHE_TTL, function () {
            $sitemap = Sitemap::create();

            $locales = Language::languages()->keys();
            $defaultLocale = config('app.locale');
            $pages = PageTranslation::with(['page'])
                ->where('page_id', theme_setting('home_page'))
                ->whereIn('locale', $locales)
                ->get()
                ->keyBy('locale');

            // Add home page
            $url = Url::create('/')->setPriority(1);
            if ($pages->isNotEmpty()) {
                $url->setLastModificationDate($pages->first()?->page->updated_at);
            }

            $sitemap->add($url);

            // Add home page with locale
            if (count($locales) > 1) {
                foreach ($locales as $locale) {
                    if ($locale === $defaultLocale) {
                        continue;
                    }

                    $sitemap->add(
                        Url::create("/{$locale}")->setPriority(1)
                            ->setLastModificationDate($pages->has($locale) ? $pages->get($locale)->updated_at : $pages->first()?->page->updated_at)
                    );
                }
            }

            return $sitemap->render();
        });

        return response($content, 200, [
            'Content-Type' => 'text/xml',
        ]);
    }

    /**
     * Generate sitemap for a specific provider and page
     */
    public function provider(string $provider, int $page = 1): Response
    {
        $modelClass = $this->sitemapRepository->get($provider);

        if (! $modelClass || ! class_exists($modelClass)) {
            abort(404, 'Sitemap provider not found');
        }

        // Check if class implements Sitemapable interface
        if (! in_array(Sitemapable::class, class_implements($modelClass) ?: [])) {
            abort(404, 'Provider does not implement Sitemapable interface');
        }

        $content = Cache::remember("sitemap.provider.{$provider}.{$page}", self::CACHE_TTL, function () use ($modelClass, $page) {
            $sitemap = Sitemap::create();

            try {
                /** @var Sitemapable $modelClass */
                $items = $modelClass::forSitemap()
                    ->skip(($page - 1) * self::ITEMS_PER_PAGE)
                    ->take(self::ITEMS_PER_PAGE)
                    ->get();

                // Add each item to the sitemap
                foreach ($items as $item) {
                    $sitemap->add($item);
                }
            } catch (\Exception $e) {
                report($e);
                throw new \RuntimeException('Error generating sitemap: '.$e->getMessage(), 500);
            }

            return $sitemap->render();
        });

        return response($content, 200, [
            'Content-Type' => 'text/xml',
        ]);
    }
}
