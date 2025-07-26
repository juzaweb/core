<div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
        <span class="info-box-icon {{ $box->getBackground() }} elevation-1">
            <i class="{{ $box->getIcon() }}"></i>
        </span>

        <div class="info-box-content">
            <span class="info-box-text">{{ $box->getTitle() }}</span>
            <span class="info-box-number">{{ number_human_format($box->getData()) }}</span>
        </div>
    </div>
</div>
