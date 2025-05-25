<div class="form-group">
    <label for="{{ $options['id'] ?? $name }}">{{ $label }}</label>
    <textarea type="text"
           name="{{ $name }}"
           id="{{ $options['id'] ?? $name }}"
           class="form-control {{ isset($options['classes']) ? implode(' ', $options['classes']) : '' }}"
           @foreach(Arr::except($options, ['classes', 'id', 'value']) as $key => $value) {{ $key }}="{{ $value }}" @endforeach
    >{{ $options['value'] ?? '' }}</textarea>
</div>
