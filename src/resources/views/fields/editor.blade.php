<div class="form-group">
    <label for="{{ $options['id'] ?? $name }}">{{ $label }}</label>
    <textarea class="form-control jw-editor {{ isset($options['classes']) ? implode(' ', $options['classes']) : '' }}"
              name="{{ $name }}"
              id="{{ $options['id'] ?? $name }}"
              rows="{{ Arr::get($options, 'rows', 5) }}"
            @foreach(Arr::except($options, ['classes', 'id', 'rows']) as $key => $value)
                {{ $key }}="{{ $value }}"
            @endforeach
    >{{ $options['value'] ?? '' }}</textarea>
</div>
