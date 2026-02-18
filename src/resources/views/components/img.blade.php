<img
    src="{{ $getPlaceHolder() }}"
    data-src="{{ $getSrc() }}"
    @if($getSrcset())
    data-srcset="{{ $getSrcset() }}"
    sizes="auto"
    @endif
    alt="{{ $alt }}"
    class="lazyload {{ $class }}"
    @if($width) width="{{ $width }}" @endif
    @if($height) height="{{ $height }}" @endif
    {{ $attributes }}
/>
