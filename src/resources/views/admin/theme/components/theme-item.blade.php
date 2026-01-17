<div class="card">
    <div class="height-200 d-flex flex-column">
        <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="{{ $theme->thumbnail ?? route('generate.thumbnail', ['text' => $theme->name, 'width' => 360, 'height' => 200]) }}" alt="{{ $theme->name }}" class="lazyload w-100 h-100">
    </div>

    <div class="card card-bottom card-borderless mb-0">
        <div class="card-header border-bottom-0">
            <div class="d-flex">
                <div class="text-dark text-uppercase font-weight-bold mr-auto">
                    {{ $theme->name }}
                </div>
                <div class="text-gray-6">
                    @if($active ?? false)
                        <button class="btn btn-secondary" disabled> {{ __('admin::translation.activated') }}</button>
                    @else
                        <button class="btn btn-primary active-theme" data-theme="{{ $theme->slug }}"> {{ __('admin::translation.activate') }}</button>
                    @endif

                    <a href="javascript:void(0)" class="btn btn-info text-white" data-toggle="modal" data-target="#theme-modal-{{ $theme->id }}"> {{ __('admin::translation.details') }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="theme-modal-{{ $theme->id }}" tabindex="-1" aria-labelledby="theme-modal-{{ $theme->id }}Label" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="theme-modal-{{ $theme->id }}Label">{{ $theme->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Thumbnail -->
                        <div class="col-md-4 text-center mb-3">
                            <img src="{{ $theme->thumbnail ?? route('generate.thumbnail', ['text' => $theme->name, 'width' => 360, 'height' => 200]) }}" alt="{{ $theme->name }}" class="img-fluid rounded shadow">
                        </div>

                        <!-- Info -->
                        <div class="col-md-8">
                            <h6 class="text-muted mb-2">{{ __('admin::translation.description') }}</h6>
                            <p class="mb-3">{{ $theme->description }}</p>

                            <h6 class="text-muted mb-2">{{ __('admin::translation.content') }}</h6>
                            <div class="p-2" style="max-height: 200px; overflow-y: auto;">
                                {!! $theme->content !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
