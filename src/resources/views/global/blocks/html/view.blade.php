@php
    /** @var \Juzaweb\Modules\Core\Models\Pages\PageBlock $block */
    $content = $block->data['content'] ?? '';
@endphp

@if($content)
    <div class="page-block-html">
        {!! $content !!}
    </div>
@endif
