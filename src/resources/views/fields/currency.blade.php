@php
    $symbol = $options['symbol'] ?? '$';
    $decimals = $options['decimals'] ?? 2;
    $thousandSeparator = $options['thousand_separator'] ?? ',';
    $decimalSeparator = $options['decimal_separator'] ?? '.';
    $symbolPosition = $options['symbol_position'] ?? 'left';
    $fieldValue = $options['value'] ?? '';
    $fieldId = $options['id'] ?? $name;
@endphp

<div class="form-group">
    <label for="{{ $fieldId }}">{{ $label }}</label>
    <div class="input-group">
        @if ($symbolPosition === 'left')
            <div class="input-group-prepend">
                <span class="input-group-text">{{ $symbol }}</span>
            </div>
        @endif

        <input type="text" name="{{ $name }}" id="{{ $fieldId }}"
            class="form-control currency-input {{ isset($options['classes']) ? implode(' ', $options['classes']) : '' }}"
            value="{{ $fieldValue }}" @if (isset($options['disabled']) && $options['disabled']) disabled @endif
            @if (isset($options['readonly']) && $options['readonly']) readonly @endif @if (isset($options['required']) && $options['required']) required @endif
            @if (isset($options['placeholder'])) placeholder="{{ $options['placeholder'] }}" @endif
            data-decimals="{{ $decimals }}" data-thousand-separator="{{ $thousandSeparator }}"
            data-decimal-separator="{{ $decimalSeparator }}"
            @foreach (Arr::except($options, ['classes', 'id', 'value', 'disabled', 'readonly', 'required', 'placeholder', 'symbol', 'decimals', 'thousand_separator', 'decimal_separator', 'symbol_position', 'help']) as $key => $value)
                {{ $key }}="{{ $value }}" @endforeach />

        @if ($symbolPosition === 'right')
            <div class="input-group-append">
                <span class="input-group-text">{{ $symbol }}</span>
            </div>
        @endif
    </div>

    @if (isset($options['help']))
        <small class="form-text text-muted">{{ $options['help'] }}</small>
    @endif
</div>
