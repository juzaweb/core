@extends('core::layouts.admin')

@section('content')
    <div id="menu-container">
        <div class="row alert alert-light p-3 no-radius">

            <div class="col-md-6 form-select-menu">
                <div class="alert-default">
                    @if ($menu)
                        {{ __('core::translation.select_menu_to_edit') }}:
                        <select name="id" id="select-menu" class="w-25 form-control load-data"
                            data-url="{{ $menuDataUrl }}">
                            <option value="{{ $menu->id }}" selected>{{ $menu->name }}</option>
                        </select>

                        {{ __('core::translation.or') }}
                    @endif

                    <a href="javascript:void(0)" class="ml-1 text-primary btn-add-menu">
                        <i class="fa fa-plus"></i> {{ __('core::translation.create_new_menu') }}
                    </a>
                </div>
            </div>

            <div class="col-md-6 form-add-menu box-hidden">
                <form action="{{ route('admin.menus.store') }}" method="post" class="form-ajax form-inline">
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" autocomplete="off" required
                            placeholder="{{ __('core::translation.menu_name') }}">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-plus"></i> {{ __('core::translation.add_menu') }}</button>
                </form>
            </div>
        </div>


        @if ($menu)
            <div class="row mt-5">
                <div class="col-md-4">
                    <h5 class="mb-2 font-weight-bold">{{ __('core::translation.items') }}</h5>

                    @php
                        $hidden = true;
                        $index = 0;
                    @endphp
                    @foreach ($boxes as $key => $box)
                        @php
                            $options = $box['options']();
                            if ($index == 0) {
                                $hidden = false;
                            } else {
                                $hidden = true;
                            }

                            $index++;
                        @endphp

                        <div class="card card-menu-items mb-2" id="menu-box-{{ $key }}">
                            <div class="card-header card-header-flex">
                                <div class="d-flex flex-column justify-content-center card-menu-title">
                                    <h5 class="mb-0 text-capitalize">{{ $options['label'] }}</h5>
                                </div>

                                <div class="ml-auto d-flex align-items-stretch card-menu-actions">
                                    <a href="#" class="card-menu-show">
                                        <i class="fa fa-sort-down"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="card-body @if ($hidden) box-hidden @endif">
                                @component('core::admin.menu.components.model-box', [
                                    'key' => $key,
                                    'box' => $box,
                                ])
                                @endcomponent
                            </div>
                        </div>
                    @endforeach

                    <div class="card card-menu-items" id="menu-box-custom">
                        <div class="card-header card-header-flex">
                            <div class="d-flex flex-column justify-content-center card-menu-title">
                                <h5 class="mb-0 text-capitalize">{{ __('core::translation.custom_link') }}</h5>
                            </div>

                            <div class="ml-auto d-flex align-items-stretch card-menu-actions">
                                <a href="javascript:void(0)" class="card-menu-show">
                                    <i class="fa fa-sort-down"></i>
                                </a>
                            </div>
                        </div>

                        <div class="card-body box-hidden">
                            <form action="" method="post" class="form-menu-block" data-template="custom">

                                @component('core::admin.menu.components.custom-box')
                                @endcomponent

                                <button type="submit" class="btn btn-primary btn-sm mt-2 px-3">
                                    <i class="fa fa-plus"></i> {{ __('core::translation.add_to_menu') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <h5 class="mb-2 font-weight-bold">{{ __('core::translation.structure') }}</h5>

                    <form action="{{ route('admin.menus.update', [$websiteId, $menu->id]) }}" method="post"
                        class="form-ajax form-menu-structure">
                        <input type="hidden" name="id" value="{{ $menu->id }}">
                        <input type="hidden" name="reload_after_save" value="0">

                        @method('PUT')

                        <div class="card">
                            <div class="card-header bg-light pb-1">
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label for="name"
                                                class="col-sm-3">{{ __('core::translation.menu_name') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="name" id="name" class="form-control"
                                                    value="{{ $menu->name ?? '' }}" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        {{ Field::language(__('core::translation.language'), 'locale') }}
                                    </div>
                                </div>
                            </div>

                            <div class="card-body" id="form-menu">
                                <div class="dd" id="jw-menu-builder">
                                    <ol class="dd-list">
                                        @foreach ($menu->items->sortBy('display_order') as $item)
                                            @component('core::admin.menu.components.menu-item', [
                                                'item' => $item,
                                                'register' => true,
                                                'children' => $item->children,
                                            ])
                                                @if ($item->is_custom)
                                                    @component('core::admin.menu.components.items.custom', [
                                                        'item' => $item,
                                                    ])
                                                    @endcomponent
                                                @else
                                                    @component('core::admin.menu.components.items.model', [
                                                        'item' => $item,
                                                    ])
                                                    @endcomponent
                                                @endif
                                            @endcomponent
                                        @endforeach
                                    </ol>
                                </div>

                                <hr>

                                @foreach ($navMenus as $key => $navMenu)
                                    <div class="form-check mb-2">
                                        <label class="form-check-label">
                                            <input class="form-check-input" name="location[]" type="checkbox"
                                                value="{{ $key }}"
                                                @if (isset($location[$key]) && $location[$key] == $menu->id) checked @endif>
                                            {{ $navMenu['label'] }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="card-footer">
                                <div class="btn-group">
                                    <a href="javascript:void(0)" class="text-danger delete-menu"
                                        data-id="{{ $menu->id }}"
                                        data-name="{{ $menu->name }}">{{ __('core::translation.delete_menu') }}</a>
                                </div>

                                <div class="btn-group float-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save"></i> {{ __('core::translation.save') }}
                                    </button>
                                </div>
                            </div>

                            <textarea name="content" id="items-output" class="form-control box-hidden"></textarea>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script type="text/html" id="template-menu-item-custom">
        @component('core::admin.menu.components.items.custom', [
            'item' => (object) [
                    'label' => '{label}',
                    'link' => '{link}',
                    'target' => '_self'
                ]
            ])
        @endcomponent
    </script>

    <script type="text/html" id="template-menu-item-model">
        @component('core::admin.menu.components.items.model', [
            'item' => (object) [
                    'label' => '{label}',
                    'link' => '{link}',
                    'target' => '_self',
                    'menuable_class_name' => '{menuable_class_name}',
                    'edit_url' => '{edit_url}',
                ]
            ])
        @endcomponent
    </script>

    <script type="text/html" id="template-menu-item">
        @component('core::admin.menu.components.menu-item', [
            'item' => (object) [
                'label' => '{label}',
                'target' => '_self',
                'element_data' => '{attributes}'
            ],
            'register' => false,
        ])
            {slot}
        @endcomponent
    </script>

    <script nonce="{{ csp_script_nonce() }}">
        $(function() {
            $('#select-menu').on('change', function() {
                var url = "{{ route('admin.menus.show', [$websiteId, '__ID__']) }}"
                    .replace('__ID__', $(this).val());
                window.location.href = url;
            });
        });
    </script>
@endsection
