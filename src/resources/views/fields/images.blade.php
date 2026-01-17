<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $label ?? $name }}</h3>
    </div>

    <div class="card-body">
        @php
            $paths = $options['value'] ?? [];
        @endphp

        <div class="form-images">
            <input type="hidden" class="input-name" value="{{ $name }}[]">
            <div class="images-list">
                @foreach ($paths as $path)
                    @component('core::fields.components.image-item', [
                        'name' => "{$name}[]",
                        'path' => $path,
                        'url' => upload_url($path),
                    ])
                    @endcomponent
                @endforeach

                <div class="image-item add-image-container">
                    <button type="button" class="btn btn-info btn-block add-image-images-modal" data-type="image">
                        <i class="fa fa-images fa-2x mb-2"></i>
                        <div>{{ __('core::translation.add_images') }}</div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
