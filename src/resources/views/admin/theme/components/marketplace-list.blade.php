{{-- Marketplace List: {{ count($themes) }} themes --}}
@foreach ($themes as $theme)
    <div class="col-md-4 p-2 marketplace-item">
        @component('core::admin.theme.components.marketplace-item', ['theme' => $theme, 'installedThemes' => $installedThemes])
        @endcomponent
    </div>
@endforeach
