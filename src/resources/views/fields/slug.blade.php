<div class="form-group">
    <label for="{{ $options['id'] ?? $name }}">{{ $label }}</label>

    <div class="input-group">
        <input type="{{ $options['type'] ?? 'text' }}"
               name="{{ $name }}"
               id="{{ $options['id'] ?? $name }}"
               class="form-control slug-target {{ isset($options['classes']) ? implode(' ', $options['classes']) : '' }}"
               @foreach(Arr::except($options, ['classes', 'id', 'label', 'help']) as $key => $value)
                   {{ $key }}="{{ $value }}"
               @endforeach
        >

        <div class="input-group-append">
            <button type="button" class="input-group-text edit-slug" id="edit-{{ $options['id'] ?? $name }}">
                <i class="fas fa-edit"></i>
            </button>
        </div>
    </div>

    @if(isset($options['help']))
        <small class="form-text text-muted">{{ $options['help'] }}</small>
    @endif
</div>
