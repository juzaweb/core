@foreach($blocks ?? [] as $block)
    @php
        $pageBlock = PageBlock::get($block->block);
    @endphp

    @if($pageBlock === null)
        @continue
    @endif

    {{ $pageBlock->view($block) }}
@endforeach