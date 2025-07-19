@php
    $languages = \Juzaweb\Core\Models\Language::languages();
    $name = $name ?? 'locale';
    $locale = $options['value'] ?? request()->get('locale', config('translatable.fallback_locale'));
@endphp
<div class="form-group">
    <label for="{{ $options['id'] ?? $name }}">{{ $label ?? __('Language') }}</label>
    <select
        name="{{ $name }}"
        id="{{ $options['id'] ?? $name }}"
        class="form-control select-language {{ isset($options['classes']) ? implode(' ', $options['classes']) : '' }}"
        @foreach(Arr::except($options, ['classes', 'id', 'name']) as $key => $value) {{ $key }}="{{ $value }}" @endforeach
    >
        @foreach($languages as $key => $language)
            <option value="{{ $key }}" @selected($locale == $key)>
                {{ $language->name }}
            </option>
        @endforeach
    </select>
</div>