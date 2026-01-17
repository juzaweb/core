@extends('core::layouts.admin')

@section('content')
    <div class="row" id="widget-container">
        <div class="col-md-4">
            <x-language-card label="{{ __('core::translation.language') }}" locale="{{ $locale }}"/>

            @foreach($widgets as $key => $widget)
                @component('core::admin.widget.components.widget_item', [
                        'widget' => $widget,
                        'key' => $key,
                        'sidebars' => $sidebars
                    ])
                @endcomponent
            @endforeach
        </div>

        <div class="col-md-8">
            @php
                $index = 0;
            @endphp
            @foreach($sidebars as $key => $sidebar)
                @component('core::admin.widget.components.sidebar_item', [
                    'item' => $sidebar,
                    'show' => $index == 0,
                    'sidebarWidgets' => $sidebarWidgets,
                    'locale' => $locale,
                ])
                @endcomponent

                @php
                    $index ++;
                @endphp
            @endforeach
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/html" id="sidebar-widget-form-template">
        @component('core::admin.widget.components.sidebar_widget_item', [
            'widget' => new \Juzaweb\Modules\Core\Support\Entities\Widget(
                '{widget_key}',
                [
                    'label' => '{widget_label}',
                    'description' => '{widget_description}',
                ]
            ),
            'key' => '{key}',
            'data' => []
        ])
        @endcomponent
    </script>
@endsection
