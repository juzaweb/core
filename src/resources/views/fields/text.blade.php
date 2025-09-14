<div class="form-group">
    <label for="{{ $options['id'] ?? $name }}">{{ $label }}</label>
    <input type="{{ $options['type'] ?? 'text' }}"
           name="{{ $name }}"
           id="{{ $options['id'] ?? $name }}"
           class="form-control {{ isset($options['classes']) ? implode(' ', $options['classes']) : '' }}"
           @foreach(Arr::except($options, ['classes', 'id']) as $key => $value) {{ $key }}="{{ $value }}" @endforeach
    >
    @if(isset($options['help']))
        <small class="form-text text-muted">{{ $options['help'] }}</small>
    @endif
</div>