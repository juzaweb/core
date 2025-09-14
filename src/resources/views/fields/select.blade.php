<div class="form-group">
    @php
        $fieldValue = ($options['value'] ?? null);
        if ($fieldValue && !is_array($fieldValue)) {
            $fieldValue = [$fieldValue];
        }
    @endphp
    <label for="{{ $options['id'] ?? $name }}">{{ $label }}</label>
    <select
            name="{{ $name }}"
            id="{{ $options['id'] ?? $name }}"
            class="form-control {{ Arr::get($options, 'autocomplete', true) ? 'select2' : '' }} {{ isset($options['classes']) ? implode(' ', $options['classes']) : '' }}"
            @if(isset($options['disabled']) && $options['disabled']) disabled @endif
            @foreach(Arr::except($options, ['classes', 'id', 'options', 'disabled', 'value']) as $key => $value) {{ $key }}="{{ $value }}" @endforeach
    >
        @foreach($options['options'] ?? [] as $key => $val)
            <option value="{{ $key }}" @selected(in_array($key, $fieldValue ?? []))>
                {{ $val }}
            </option>
        @endforeach
    </select>
    @if(isset($options['help']))
        <small class="form-text text-muted">{{ $options['help'] }}</small>
    @endif
</div>
