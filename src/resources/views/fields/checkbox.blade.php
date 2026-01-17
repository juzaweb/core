<div class="custom-control custom-switch jw-checkbox">
    <input type="checkbox"
        class="custom-control-input {{ isset($options['classes']) ? implode(' ', $options['classes']) : '' }}"
        id="{{ $options['id'] ?? $name }}" @checked($options['value'] ?? '0') @disabled(isset($options['disabled']) && $options['disabled'])
        @foreach (Arr::except($options, ['classes', 'id', 'value', 'disabled']) as $key => $value) {{ $key }}="{{ $value }}" @endforeach>
    <input type="hidden" class="jw-checkbox-input" name="{{ $name }}"
        value="{{ $options['value'] ?? '0' ? 1 : 0 }}" />
    <label class="custom-control-label" for="{{ $options['id'] ?? $name }}">{{ $label }}</label>
</div>
