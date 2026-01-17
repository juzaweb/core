<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="box-{{ $key }}-latest-label" href="#box-{{ $key }}-latest-tab" data-toggle="tab">{{ __('core::translation.latest') }}</a>
    </li>

    <li class="nav-item">
        <a class="nav-link" id="box-{{ $key }}-search-label" href="#box-{{ $key }}-search-tab" data-toggle="tab">{{ __('core::translation.search') }}</a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade p-2 active show" id="box-{{ $key }}-latest-tab" role="tabpanel" aria-labelledby="box-{{ $key }}-latest-label">
        <form action="" method="post"
              class="form-menu-block"
              data-template="model"
              data-key="{{ $key }}"
        >
            @php
                $items = $box['class']::whereInMenuBox()->latest()->limit(10)->get();
            @endphp

            @foreach($items ?? [] as $item)
                <div class="form-check mt-1">
                    <label class="form-check-label">
                        <input class="form-check-input reset-after-add select-all-{{ $key }}"
                               type="checkbox"
                               name="items[]"
                               value="{{ $item->id }}"
                               data-menuable_id="{{ $item->id }}"
                               data-key="{{ $key }}"
                               data-target="_self"
                               data-label="{{ $item->name ?? $item->title }}"
                               data-edit_url="{{ $item->getEditUrl() }}"
                               data-menuable_class_name="{{ class_basename($item) }}"
                               data-menuable_class="{{ get_class($item) }}"
                        >
                        {{ $item->name ?? $item->title }}
                    </label>
                </div>
            @endforeach

            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input reset-after-add select-all-checkbox" type="checkbox" data-select="select-all-{{ $key }}">
                            {{ __('core::translation.select_all') }}
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-sm mt-2 px-3">
                <i class="fa fa-plus"></i> {{ __('core::translation.add_to_menu') }}
            </button>

        </form>
    </div>

    @php
        $dataUrl = route(
            'admin.load-box',
            [
                'token' => encrypt(['box' => $key]),
            ]
        )
    @endphp

    <div class="tab-pane fade p-2" id="box-{{ $key }}-search-tab" role="tabpanel" aria-labelledby="box-{{ $key }}-search-label">
        <input class="form-control menu-box-model-search" type="text" placeholder="{{ __('core::translation.search') }}" data-class="{{ $box['class'] }}" data-key="{{ $key }}" data-url="{{ $dataUrl }}">

        <form action="" method="post"
              class="form-menu-block"
              data-template="model"
              data-key="{{ $key }}"
        >

            <div class="box-tab-search-result mt-2">

            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input reset-after-add select-all-checkbox"
                                   type="checkbox"
                                   data-select="select-all-search-{{ $key }}">
                            {{ __('core::translation.select_all') }}
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-sm mt-2 px-3">
                <i class="fa fa-plus"></i> {{ __('core::translation.add_to_menu') }}
            </button>

        </form>

    </div>

</div>
