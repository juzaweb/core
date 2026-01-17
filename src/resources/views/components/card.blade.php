<div class="card">
    @if(isset($title))
    <div class="card-header">
        <h3 class="card-title w-100">
            @if(isset($icon))
                <i class="fas fa-{{ $icon }} mr-1"></i>
            @endif
            {{ $title }}
        </h3>

        @if($description)
        <p class="card-subtitle mb-2 text-muted" style="font-size: 12px">{{ $description }}</p>
        @endif
    </div>
    @endif

    <div class="card-body">
        {{ $slot }}
    </div>
</div>
