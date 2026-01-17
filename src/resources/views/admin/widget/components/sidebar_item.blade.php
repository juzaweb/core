<div class="card sidebar-item" id="sidebar-{{ $item->get('key') }}">
    <form action="{{ route('admin.widgets.update', [$websiteId, $item->get('key')]) }}" method="post" class="form-ajax">
        @method('PUT')

        <input type="hidden" name="locale" value="{{ $locale }}">

        <div class="card-header">
            <h5>{{ $item->get('label') }}</h5>

            <div class="text-right right-actions">
                <a href="javascript:void(0)" class="show-edit-form">
                    <i class="fa fa-sort-down fa-2x"></i>
                </a>
            </div>
        </div>

        <div class="card-body @if(empty($show)) box-hidden @endif">
            <div class="dd jw-widget-builder" data-key="{{ $item->get('key') }}">

                @php
                    $widgets = $sidebarWidgets->get($item->get('key'), []);
                @endphp

                <ol class="dd-list">
                    @foreach($widgets as $key => $widget)
                        @php
                            $widgetData = Widget::get($widget->widget ?? 'null');
                        @endphp

                        @if($widgetData === null)
                            @continue
                        @endif

                        @php
                            $data = $widget->data ?? [];
                            $data['id'] = $widget->id;
                            $data['label'] = $widget->label;
                            $data['locale'] = $locale;
                        @endphp

                        @component('core::admin.widget.components.sidebar_widget_item', [
                            'widget' => $widgetData,
                            'sidebar' => $item,
                            'key' => $key,
                            'data' => $data,
                        ])
                        @endcomponent
                    @endforeach
                </ol>
            </div>

            <button type="submit" class="btn btn-success">
                <i class="fa fa-save"></i> {{ __('core::translation.save') }}
            </button>

        </div>
    </form>
</div>
