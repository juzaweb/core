<div class="form-group">
    <label for="{{ $options['id'] ?? $name }}">{{ $label }}</label>
    <select
            name="{{ $name }}"
            id="{{ $options['id'] ?? $name }}"
            class="form-control {{ Arr::get($options, 'autocomplete', true) ? 'select2' : '' }} {{ isset($options['classes']) ? implode(' ', $options['classes']) : '' }}"
            @foreach(Arr::except($options, ['classes', 'id']) as $key => $value) {{ $key }}="{{ $value }}" @endforeach
    >
        @foreach($options['options'] ?? [] as $key => $val)
            <option value="{{ $key }}" @selected(($options['value'] ?? null) == $key)>{{ $value }}</option>
        @endforeach
    </select>
</div>
