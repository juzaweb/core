@foreach($actions as $action)
    <a href="{{ $action->getUrl() }}"
       class="btn btn-{{ $action->getColor() }} btn-sm datatables-row-{{ $action->getType() }}"
       data-action="{{ $action->getAction() }}"
    >
        <i class="fas fa-{{ $action->getIcon() }}"></i> {{ $action->getLabel() }}
    </a>
@endforeach
