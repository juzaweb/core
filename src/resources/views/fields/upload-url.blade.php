<div class="form-group">
    <label for="{{ $options['id'] ?? $name }}">{{ $label }}</label>

    <div class="input-group mb-2">
        <input type="{{ $options['type'] ?? 'text' }}" name="{{ $name }}" id="{{ $options['id'] ?? $name }}"
            class="form-control upload-url-input {{ isset($options['classes']) ? implode(' ', $options['classes']) : '' }}"
            placeholder="{{ $options['placeholder'] ?? 'https://' }}"
            @foreach (Arr::except($options, ['classes', 'id', 'placeholder']) as $key => $value) {{ $key }}="{{ $value }}" @endforeach>

        <div class="input-group-append">
            <button type="button" class="btn btn-info btn-sm upload-url-media-picker"
                data-input="{{ $options['id'] ?? $name }}" data-type="{{ $uploadType ?? 'file' }}">
                <i class="fas fa-images"></i> {{ __('admin::translation.media_library') }}
            </button>
        </div>
    </div>
    @if (isset($options['help']))
        <small class="form-text text-muted">{{ $options['help'] }}</small>
    @endif
</div>
