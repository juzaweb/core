<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Http\Controllers;

use Juzaweb\Modules\Core\Traits\HasSessionResponses;

abstract class AdminController extends Controller
{
    use HasSessionResponses;

    protected function getFormLanguage(): string
    {
        return request()->get('locale', config('translatable.fallback_locale'));
    }
}
