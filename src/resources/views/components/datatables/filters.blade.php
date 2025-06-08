<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="">
            <div class="form-row align-items-end">
                {{ $slot }}

                <div class="form-group col-md-1">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>

                <div class="form-group col-md-1">
                    <a href="{{ request()->url() }}" class="btn btn-light border btn-block">
                        <i class="fas fa-sync-alt"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>