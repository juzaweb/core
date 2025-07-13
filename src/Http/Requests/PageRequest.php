<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Juzaweb\Core\Models\Enums\PageStatus;
use Juzaweb\Core\Models\Language;

class PageRequest extends FormRequest
{
    public function rules(): array
    {
        $locale = $this->input('locale', config('translatable.fallback_locale'));

        return [
            'locale' => ['required', 'string', Rule::in(Language::languages()->keys())],
            'status' => ['required', 'string', Rule::in(array_keys(PageStatus::all()))],
            "{$locale}.title" => ['required', 'string', 'max:255'],
            "{$locale}.content" => ['required', 'string'],
            "{$locale}.thumbnail" => ['nullable', 'string'],
        ];
    }
}
