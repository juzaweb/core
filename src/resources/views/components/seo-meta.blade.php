<x-card :title="__('SEO Metas')">
    {{ Field::text(__('Meta Title'), "seo[{$locale}][title]") }}

    {{ Field::textarea(__('Meta Description'), "seo[{$locale}][description]") }}

    {{ Field::image(__('Open Graph Image'), "seo[{$locale}][image]") }}
</x-card>