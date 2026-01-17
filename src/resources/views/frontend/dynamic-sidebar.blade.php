@foreach($sidebars as $sidebar)
    @php
        $widget = Widget::get($sidebar->widget);
        if ($widget === null) {
            continue;
        }
    @endphp

    {{ $widget->view($sidebar) }}
@endforeach
