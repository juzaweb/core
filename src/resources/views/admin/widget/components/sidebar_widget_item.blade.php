<li class="dd-item" data-widget="{{ $widget->get('key') }}" data-key="{{ $key }}" id="dd-item-{{ $key }}">
    <div class="dd-handle">
        <span>{{ $widget->get('label') }}</span>
        <div class="dd-nodrag">
            <a href="javascript:void(0)" class="show-item-form">
                <i class="fa fa-sort-down"></i>
            </a>
        </div>
    </div>

    <div class="form-item-edit dd-nodrag box-hidden">
        @php
            $data = $data ?? [];
            $data['key'] = $key;
        @endphp

        {{ $widget->form($data) }}

        <input type="hidden" name="content[{{ $key }}][id]" value="{{ $data['id'] ?? '' }}">
        <input type="hidden" name="content[{{ $key }}][widget]" value="{{ $widget->get('key') }}">
        <input type="hidden" name="content[{{ $key }}][key]" value="{{ $key }}">

        <a href="javascript:void(0)" class="delete-item-form text-danger">
            <i class="fa fa-times"></i> {{ __('admin::translation.delete') }}
        </a>
    </div>
</li>
