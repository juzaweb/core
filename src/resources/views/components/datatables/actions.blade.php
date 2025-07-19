@foreach($actions as $action)
    <a href="{{ $action->getUrl() }}"
       class="btn btn-{{ $action->getColor() }} btn-sm datatables-row-action datatables-row-{{ $action->getType() }} @if($action->isDisabled()) disabled @endif"
       data-type="{{ $action->getType() }}"
       data-id="{{ $model->getKey() }}"
       data-endpoint="{{ $endpoint }}"
       @if($action->getAction()) data-action="{{ $action->getAction() }}" @endif
    >
        <i class="{{ $action->getIcon() }}"></i> {{ $action->getLabel() }}
    </a>
@endforeach
