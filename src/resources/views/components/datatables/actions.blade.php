@foreach($actions as $action)
    <a href="{{ $action->getUrl() }}" class="btn btn-primary btn-sm">
        <i class="fas fa-{{ $action->getIcon() }}"></i> {{ $action->getLabel() }}
    </a>
@endforeach
