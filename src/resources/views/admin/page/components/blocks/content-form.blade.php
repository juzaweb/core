<div class="page-block-content">
    <div id="page-block-builder-nestable-{{ $key }}" class="dd jw-widget-builder">
        <ol class="dd-list">
            @foreach($items as $index => $item)
                @php
                    $block = PageBlock::get($item->block);
                @endphp

                @component('core::admin.page.components.blocks.page-block-item', [
                    'key' => 'block-' . $item->id,
                    'block' => $block,
                    'contentKey' => $contentKey,
                    'value' => $item,
                ])

                @endcomponent
            @endforeach
        </ol>
    </div>

    <div class="widget-button w-100 text-center">
        <div class="dropdown">
            <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenuButton-{{ $key }}"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ __('core::translation.add_block') }}
            </button>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-{{ $key }}">
                @foreach($blocks as $bkey => $b)
                    <a
                            href="javascript:void(0)"
                            class="dropdown-item add-block-data"
                            data-block="{{ $bkey }}"
                            data-key="{{ $key }}"
                            data-content_key="{{ $contentKey }}"
                    >{{ $b->get('label') }}</a>
                @endforeach
            </div>
        </div>
    </div>
</div>

