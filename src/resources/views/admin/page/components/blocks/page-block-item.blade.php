<li class="dd-item" id="dd-item-{{ $key }}" data-label="{{ $block->get('label') }}">
    <div class="dd-handle">
        <span>{{ $block->get('label') }}</span>
        <div class="dd-nodrag">
            <div class="block-action-button">
                <a href="javascript:void(0)" class="show-form-block">
                    <i class="fa fa-edit"></i> {{ __('admin::translation.edit') }}
                </a>

                <a href="javascript:void(0)" class="remove-form-block text-danger">
                    <i class="fa fa-trash"></i> {{ __('admin::translation.delete') }}
                </a>
            </div>
        </div>
    </div>

    <div class="form-block-edit dd-nodrag box-hidden" id="page-block-{{ $key }}">
        @php
        $data = ['name' => "blocks[{$contentKey}][{$key}]"];
        if (isset($value)) {
            $data['label'] = $value->label;
            $data = array_merge($data, $value->data ?? []);
        }
        @endphp

        {{ $block->form($data) }}

        <input type="hidden" name="blocks[{{ $contentKey }}][{{ $key }}][block]" value="{{ $block->get('key') }}">
        <input type="hidden" name="blocks[{{ $contentKey }}][{{ $key }}][id]" value="{{ $value->id ?? '' }}">
    </div>
</li>
