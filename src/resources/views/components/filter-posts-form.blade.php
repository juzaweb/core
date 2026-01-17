{{ Field::select(__('core::translation.categories'), "{$name}[categories][]", ['value' => $data['categories'] ?? []])
->dataUrl(load_data_url(\Juzaweb\Modules\Blog\Models\Category::class, 'name'))
->multiple()
->dropDownList(
    \Juzaweb\Modules\Blog\Models\Category::whereIn('id', $data['categories'] ?? [])->get()->pluck('name', 'id')->toArray(),
) }}

{{ Field::select(__('core::translation.sort_by'), "{$name}[sort_by]", ['value' => $data['sort_by'] ?? 'id'])->dropDownList(
    [
        'id' => 'ID',
        'views' => 'Views',
        'created_at' => 'Created Date',
        'updated_at' => 'Updated Date',
    ]
) }}

{{ Field::select(__('core::translation.sort_order'), "{$name}[sort_order]", ['value' => $data['sort_order'] ?? 'asc'])->dropDownList(
    [
        'asc' => __('core::translation.ascending'),
        'desc' => __('core::translation.descending'),
    ]
) }}

{{ Field::text(__('core::translation.limit'), "{$name}[limit]", ['value' => $data['limit'] ?? '6', 'type' => 'number']) }}
