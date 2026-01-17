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
            class="form-control {{ Arr::get($options, 'autocomplete', false) ? 'select2-input' : '' }} {{ isset($options['classes']) ? implode(' ', $options['classes']) : '' }} @if(isset($options['data_url'])) load-data @endif"
            @if(isset($options['disabled']) && $options['disabled']) disabled @endif
            @foreach(Arr::except($options, ['classes', 'id', 'options', 'disabled', 'value', 'data_url']) as $key => $value) {{ $key }}="{{ $value }}" @endforeach
            @if(isset($options['data_url']))
            data-url="{{ $options['data_url'] }}"
            @endif
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
