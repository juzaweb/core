<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Support;

use Juzaweb\Core\Application;
use Juzaweb\Core\Contracts\Setting;
use Juzaweb\Core\Models\Language;

class LocaleRepository
{
    public function __construct(protected Application $app)
    {
    }

    public function setLocale($locale = null): ?string
    {
        if ($this->app[Setting::class]->get('multiple_language') !== 'prefix') {
            return $locale;
        }

        if (empty($locale)) {
            $locale = $this->app['request']->segment(1);
        }

        if (empty($locale) || !\is_string($locale) || !Language::languages()->has($locale)) {
            return null;
        }

        return $locale;
    }
}
