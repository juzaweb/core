<img
    src="{{ $getPlaceHolder() }}"
    data-src="{{ $getSrc() }}"
    alt="{{ $alt }}"
    class="lazyload {{ $class }}"
    @if($width) width="{{ $width }}" @endif
    @if($height) height="{{ $height }}" @endif
    {{ $attributes }}
/>
