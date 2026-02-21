<li class="repeater-item-wrapper" id="repeater-item-{{ $marker }}">
    <div class="row repeater-item">
        <div class="col-md-11">
            {{ $slot }}
        </div>

        <div class="col-md-1">
            <a href="#" class="text-danger remove-repeater-item">
                <i class="fas fa-times-circle"></i>
            </a>
        </div>
    </div>
</li>