<div class="form-check jw-checkbox">
    <input
        type="checkbox"
        class="form-check-input {{ isset($options['classes']) ? implode(' ', $options['classes']) : '' }}"
        id="{{ $options['id'] ?? $name }}"
        @checked(($options['value'] ?? '0'))
        @foreach(Arr::except($options, ['classes', 'id', 'value']) as $key => $value) {{ $key }}="{{ $value }}" @endforeach
    >
    <input type="hidden" class="jw-checkbox-input" name="{{ $name }}" value="{{ ($options['value'] ?? '0') ? 1 : 0 }}" />
    <label class="form-check-label" for="{{ $options['id'] ?? $name }}">{{ $label }}</label>
</div>