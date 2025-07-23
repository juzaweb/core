<div class="card">
    @if(isset($title))
    <div class="card-header">
        <h3 class="card-title">{{ $title }}</h3>
    </div>
    @endif

    <div class="card-body">
        {{ $slot }}
    </div>
</div>
