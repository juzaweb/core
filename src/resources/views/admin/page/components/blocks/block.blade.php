@php
    $blocks = PageBlock::all();
    $templateBlocks = $template->blocks;
    $currentTheme = active_theme();
    $key = \Illuminate\Support\Str::random(32);
@endphp

@foreach($templateBlocks as $contentKey => $block)
    @php
        $items = $model->blocks->where('container', $contentKey)->sortBy('display_order');
    @endphp

    <x-card title="{{ $block }}">
        @component('core::admin.page.components.blocks.content-form', compact(
            'key',
            'block',
            'blocks',
            'contentKey',
            'items',
            'currentTheme',
        ))
        @endcomponent
    </x-card>
@endforeach

@foreach($blocks as $bkey => $block)
    <script type="text/html" id="block-{{ $bkey }}-template">
        @component('core::admin.page.components.blocks.page-block-item', [
            // 'data' => $data,
            'key' => '{marker}',
            'block' => $block,
            'contentKey' => '{content_key}',
        ])

        @endcomponent
    </script>
@endforeach
