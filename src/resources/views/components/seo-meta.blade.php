<x-card :title="__('core::translation.seo_metas')">
    {{ Field::text(__('core::translation.meta_title'), "seo[{$locale}][title]") }}

    {{ Field::textarea(__('core::translation.meta_description'), "seo[{$locale}][description]") }}

    {{ Field::image(__('core::translation.open_graph_image'), "seo[{$locale}][image]") }}
</x-card>
