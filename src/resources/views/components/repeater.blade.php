<div class="repeater-component" data-name="{{ $name }}">
    <ul class="list-unstyled repeaters repeaters-{{ $name }}">
        @foreach($items as $index => $item)
            @component('admin::components.repeaters.item', [
                'marker' => $item->id ?? $index,
                'item' => $item,
            ])
                @component($view, [
                    'marker' => $index,
                    'item' => $item,
                ])
                @endcomponent
            @endcomponent
        @endforeach
    </ul>

    <button type="button" class="btn btn-info add-repeater-item">
        <i class="fas fa-plus"></i> {{ __('admin::translation.add_name', ['name' => $name]) }}
    </button>

    <script type="text/html" nonce="{{ csp_script_nonce() }}">
    @component('admin::components.repeaters.item', [
        'marker' => '__marker__',
    ])
            @component($view, [
                'marker' => '__marker__',
            ])
            @endcomponent
        @endcomponent
    </script>
</div>