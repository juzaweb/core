<x-card :title="__('admin::translation.seo_metas')">
    {{ Field::text(__('admin::translation.meta_title'), "seo[{$locale}][title]") }}

    {{ Field::textarea(__('admin::translation.meta_description'), "seo[{$locale}][description]") }}

    {{ Field::image(__('admin::translation.open_graph_image'), "seo[{$locale}][image]") }}
</x-card>