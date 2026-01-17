<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Juzaweb\Modules\Core\Enums\PageStatus;
use Juzaweb\Modules\Core\Models\Language;

class PageRequest extends FormRequest
{
    public function rules(): array
    {
        $templates = collect(\Juzaweb\Modules\Core\Facades\PageTemplate::all())
            ->map(fn ($item) => $item->key)
            ->values()
            ->toArray();

        return [
            'locale' => ['required', 'string', Rule::in(Language::languages()->keys())],
            'status' => ['required', 'string', Rule::in(array_keys(PageStatus::all()))],
            'blocks' => ['nullable', 'array'],
            'template' => ['nullable', Rule::in($templates)],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string', 'max:50000'],
            'thumbnail' => ['nullable', 'string'],
            'is_home' => ['nullable', 'boolean'],
        ];
    }
}
