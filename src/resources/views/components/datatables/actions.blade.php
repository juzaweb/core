<div class="btn-group">
    <button type="button"
            class="btn btn-sm btn-secondary dropdown-toggle"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
            title="Actions">
        <i class="fas fa-list"></i> {{ __('core::translation.actions') }}
    </button>

    <div class="dropdown-menu dropdown-menu-right">
        @foreach($actions as $action)
            <a href="{{ $action->getUrl() }}"
               target="{{ $action->getTarget() }}"
               class="dropdown-item datatables-row-action text-{{ $action->getColor() }} datatables-row-{{ $action->getType() }} @if($action->isDisabled()) disabled @endif"
               data-type="{{ $action->getType() }}"
               data-id="{{ $model->getKey() }}"
               data-endpoint="{{ $endpoint }}"
               @if($action->getAction()) data-action="{{ $action->getAction() }}" @endif
            >
                <i class="{{ $action->getIcon() }}"></i> {{ $action->getLabel() }}
            </a>
        @endforeach
    </div>
</div>
