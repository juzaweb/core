@foreach($themes as $theme)
    <div class="col-md-4 p-2 theme-list-item">
        @component('core::admin.theme.components.theme-item', ['theme' => $theme])

        @endcomponent
    </div>
@endforeach
