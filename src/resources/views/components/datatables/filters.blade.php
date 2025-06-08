<div class="row">
    <div class="col-md-9">
        @if(count($bulkActions) > 0)
            <div class="btn-group float-left" role="group" aria-label="User Actions">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ __('Bulk Actions') }}
                </button>
                <div class="dropdown-menu jw-datatable-bulk-actions">
                    @foreach($bulkActions as $action)
                        <a
                                class="dropdown-item jw-datatable-bulk-action jw-datatable-bulk-action_{{ $action->getAction() }}"
                                href="javascript:void(0)"
                                data-action="{{ $action->getAction() }}"
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
    <div class="col-md-3">
        <div id="jw-datatable_filter" class="jw-datatable_filter">
            <label>{{ __('Search') }}: <input type="search" class="form-control form-control-sm" placeholder="" aria-controls="jw-datatable"></label>
        </div>
    </div>
    @endif

</div>