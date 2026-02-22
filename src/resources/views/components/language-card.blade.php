<x-card class="card">
    {{ Field::language($label, 'locale', ['value' => $locale, 'label' => __('core::translation.language')]) }}

    @php
        $translate = config('translator.enable', false);
        $fallbackLocale = config('translatable.fallback_locale');
    @endphp

    @if($translate
        && $label instanceof \Juzaweb\Modules\Core\Translations\Contracts\Translatable
        && $locale != $fallbackLocale
        && $label->hasTranslation($fallbackLocale)
        && !$label->hasTranslation($locale)
    )
        <a href="javascript:void(0)" class="translate-model"
           data-id="{{ $label->getKey() }}"
           data-model="{{ encrypt(get_class($label)) }}"
           data-locale="{{ $locale }}"
           data-source="{{ $fallbackLocale }}"
        >
            <i class="fas fa-language"></i> {{ __('core::translation.translate_from_name', ['name' => config("locales.{$fallbackLocale}.name")]) }}
        </a>
    @endif
</x-card>
