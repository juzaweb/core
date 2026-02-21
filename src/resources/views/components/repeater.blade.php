<div class="repeater-component" data-name="{{ $name }}">
    <ul class="list-unstyled repeaters repeaters-{{ $name }}">
        @foreach($items as $index => $item)
            @component('core::components.repeaters.item', [
                'marker' => $item->id ?? $index,
                'item' => $item,
            ])
                @component($view, [
                    'marker' => $index,
                    'item' => $item,
                    ...$params,
                ])
                @endcomponent
            @endcomponent
        @endforeach
    </ul>

    <button type="button" class="btn btn-info add-repeater-item">
        <i class="fas fa-plus"></i> {{ __('core::translation.add_name', ['name' => $label ?? $name]) }}
    </button>

    <script type="text/html" nonce="{{ csp_script_nonce() }}">
    @component('core::components.repeaters.item', [
        'marker' => '__marker__',
    ])
            @component($view, [
                'marker' => '__marker__',
                ...$params,
            ])
            @endcomponent
        @endcomponent
    </script>
</div>
