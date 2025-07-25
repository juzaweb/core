<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $label ?? $name }}</h3>
    </div>

    <div class="card-body">
        @php
            $paths = $value ?? [];
        @endphp

        <div class="form-images">
            <input type="hidden" class="input-name" value="{{ $name }}[]">
            <div class="images-list">
                @foreach($paths as $path)
                    @component('core::fields.components.image-item', [
                        'name' => "{$name}[]",
                        'path' => $path,
                        'url' => upload_url($path),
                    ])

                    @endcomponent
                @endforeach

                <div class="image-item border">
                    <a href="javascript:void(0)" class="text-secondary add-image-images">
                        <i class="fa fa-plus fa-5x"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
