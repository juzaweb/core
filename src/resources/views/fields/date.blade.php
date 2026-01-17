
<div class="form-group">
    <label for="{{ $options['id'] ?? $name }}">{{ $label }}</label>
    <input type="text"
           name="{{ $name }}"
           id="{{ $options['id'] ?? $name }}"
           class="form-control jw-datepicker {{ isset($options['classes']) ? implode(' ', $options['classes']) : '' }}"
           @foreach(Arr::except($options, ['classes', 'id', 'label', 'type']) as $key => $value) {{ $key }}="{{ $value }}" @endforeach
    >
    @if(isset($options['help']))
        <small class="form-text text-muted">{{ $options['help'] }}</small>
    @endif
</div>
