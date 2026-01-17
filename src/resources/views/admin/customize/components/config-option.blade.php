<div class="theme-editor__panel-body">
    <div class="ui-stack ui-stack--vertical next-tab__panel--grow">
        <div class="ui-stack-item ui-stack-item--fill">
            <section class="next-card theme-editor__card">
                <ul class="theme-editor-action-list theme-editor-action-list--divided theme-editor-action-list--rounded">
                    @foreach($panels as $key => $panel)
                        @component('admin::admin.customize.components.action-item', [
                            'title' => $panel->get('title'),
                            'key' => $key,
                            'id' => 'panel-' . $key,
                        ])
                        @endcomponent
                    @endforeach
                </ul>
            </section>
        </div>
    </div>
</div>

@foreach($panels as $key => $panel)

    @component('admin::admin.customize.components.editor-panel', [
        'key' => $key,
        'id' => 'panel-' . $key,
        'panel' => $panel
    ])

    @endcomponent

@endforeach
