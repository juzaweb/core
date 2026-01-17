<li class="dd-item"
    {!! $item->element_data ?? '' !!}
>
    <div class="dd-handle">
        <span>{{ $item->label }}</span>
        <a href="javascript:void(0)" class="dd-nodrag show-menu-edit">
            <i class="fa fa-sort-down"></i>
        </a>

        {{--<a href="javascript:void(0)" class="dd-nodrag text-danger delete-menu-item">
            <i class="fa fa-trash"></i>
        </a>--}}
    </div>

    <div class="form-item-edit box-hidden">
        {{ $slot }}

        <div class="form-group">
            <label class="col-form-label">{{ __('admin::translation.target') }}</label>
            <select class="form-control menu-data" name="target" data-name="target">
                <option value="_self" @if($item->target == '_self') selected @endif>{{ __('admin::translation.open_in_self_tab') }}</option>
                <option value="_blank" @if($item->target == '_blank') selected @endif>{{ __('admin::translation.open_in_new_tab') }}</option>
            </select>
        </div>

        <a href="javascript:void(0)" class="text-danger delete-menu-item">{{ __('admin::translation.delete') }}</a>
        <a href="javascript:void(0)" class="text-info close-menu-item">{{ __('admin::translation.cancel') }}</a>
    </div>

    @if(isset($children) && $children->isNotEmpty())
        <ol class="dd-list">
        @foreach($children->sortBy('display_order') as $child)
            @component('admin::admin.menu.components.menu-item', ['item' => $child, 'children' => $child->children])
                @if($child->is_custom)
                    @component('admin::admin.menu.components.items.custom', [
                        'item' => $child
                    ])
                    @endcomponent
                @else
                    @component('admin::admin.menu.components.items.model', [
                        'item' => $child
                    ])
                    @endcomponent
                @endif
            @endcomponent
        @endforeach
        </ol>
    @endif

</li>
