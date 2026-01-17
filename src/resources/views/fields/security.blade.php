<div class="form-group">
    <label for="{{ $options['id'] ?? $name }}">{{ $label }}</label>

    @php
        $originValue = $options['value'] ?? $options['default'] ?? '';
        if ($originValue) {
            if (strlen($originValue) < 7) {
                $value = str_repeat('*', strlen($originValue));
            } else {
                $value = Str::mask($originValue, '*', 3, -3);
            }
        }
    @endphp

    <div class="input-group">
        <input type="{{ $options['type'] ?? 'text' }}"
               name="{{ $name }}"
               id="{{ $options['id'] ?? $name }}"
               class="form-control {{ isset($options['classes']) ? implode(' ', $options['classes']) : '' }}"
               placeholder="{{ $value ?? '' }}"
        @foreach(Arr::except($options, ['classes', 'id', 'label', 'help', 'value']) as $key => $item)
            @continue(!$item)

            {{ $key }}="{{ $item }}"
        @endforeach
        >

        <div class="input-group-append">
            <button type="button" class="input-group-text edit-security">
                <i class="fas fa-edit"></i>
            </button>
        </div>
    </div>

    @if(isset($options['help']))
        <small class="form-text text-muted">{{ $options['help'] }}</small>
    @endif
</div>
