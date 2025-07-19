<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-9">
        @if(count($bulkActions) > 0)
            <div class="btn-group float-left jw-datatable-bulk-actions" role="group" aria-label="User Actions">
                <button type="button"
                        class="btn btn-secondary dropdown-toggle"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                        disabled
                >
                    {{ __('Bulk Actions') }}
                </button>
                <div class="dropdown-menu">
                    @foreach($bulkActions as $action)
                        <a
                                class="dropdown-item jw-datatable-bulk-action jw-datatable-bulk-action_{{ $action->getAction() }} {{ $action->getColor() ? 'text-' . $action->getColor() : '' }}"
                                href="javascript:void(0)"
                                data-action="{{ $action->getAction() }}"
                                data-type="{{ $action->getType() }}"
                                data-endpoint="{{ $endpoint }}"
                        >
                            <i class="{{ $action->getIcon() }}"></i>

                            {{ $action->getLabel() }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    @if($searchable)
    <div class="col-xs-12 col-sm-12 col-md-3">
        <div id="jw-datatable_filter" class="jw-datatable_filter">
            <label>{{ __('Search') }}: <input type="search" class="form-control form-control-sm" placeholder="" aria-controls="jw-datatable"></label>
        </div>
    </div>
    @endif

</div>