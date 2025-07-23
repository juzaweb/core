<x-card class="card">
    {{ Field::language($label, 'locale', ['value' => $locale, 'label' => __('Language')]) }}

    @php
        $translate = config('services.translate.enabled', false);
        $fallbackLocale = config('translatable.fallback_locale');
    @endphp

    @if($translate && $label instanceof \Juzaweb\Translations\Contracts\Translatable && $locale != $fallbackLocale)
        <a href="javascript:void(0)" class="translate-model"
           data-id="{{ $label->getKey() }}"
           data-model="{{ get_class($label) }}"
           data-locale="{{ $locale }}"
        >
            <i class="fas fa-language"></i> {{ __('Translate from :name', ['name' => config("locales.{$fallbackLocale}.name")]) }}
        </a>
    @endif
</x-card>
