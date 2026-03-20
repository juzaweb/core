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

namespace Juzaweb\Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Juzaweb\Modules\Core\Enums\PageStatus;
use Juzaweb\Modules\Core\Facades\PageTemplate;
use Juzaweb\Modules\Core\Translations\Models\Language;

class PageRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->has('title')) {
            $title = $this->input('title');
            if (is_string($title)) {
                $title = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $title);
                $merge['title'] = strip_tags($title);
            }
        }

        if ($this->has('content')) {
            $content = $this->input('content');
            if (is_string($content)) {
                $merge['content'] = clean_html($content);
            }
        }

        if (! empty($merge)) {
            $this->merge($merge);
        }
    }

    public function rules(): array
    {
        $templates = collect(PageTemplate::all())
            ->map(fn ($item) => $item->key)
            ->values()
            ->toArray();

        return [
            'locale' => ['required', 'string', Rule::in(Language::languages()->keys())],
            'status' => ['required', 'string', Rule::in(array_keys(PageStatus::all()))],
            'blocks' => ['nullable', 'array'],
            'template' => ['nullable', Rule::in($templates)],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:100'],
            'content' => ['nullable', 'string', 'max:50000'],
            'thumbnail' => ['nullable', 'string'],
            'is_home' => ['nullable', 'boolean'],
        ];
    }
}
