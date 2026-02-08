<div class="card">
    <div class="height-200 d-flex flex-column position-relative">
        @if ($theme['is_free'])
            <span class="badge badge-success price-badge">{{ __('core::translation.free') }}</span>
        @else
            <span class="badge badge-primary price-badge">${{ number_format($theme['price'], 2) }}</span>
        @endif

        <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
            data-src="{{ $theme['thumbnail'] ?? 'https://placehold.co/530x300?text=' . urlencode($theme['title']) }}"
            alt="{{ $theme['title'] }}" class="lazyload w-100 h-100">
    </div>

    <div class="card card-bottom card-borderless mb-0">
        <div class="card-header border-bottom-0">
            <div class="d-flex flex-column">
                <div class="text-dark text-uppercase font-weight-bold mb-2">
                    {{ $theme['title'] }}
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="text-muted small">
                        <i class="fa fa-star text-warning"></i> {{ number_format($theme['rating'], 1) }}
                        ({{ $theme['total_reviews'] }})
                    </div>
                    <div class="text-muted small">
                        <i class="fa fa-download"></i> {{ number_format($theme['sales']) }}
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="javascript:void(0)" class="btn btn-sm btn-info text-white" data-toggle="modal"
                        data-target="#marketplace-modal-{{ $theme['id'] }}">
                        <i class="fa fa-info-circle"></i> {{ __('core::translation.details') }}
                    </a>
                    @if ($theme['is_free'])
                        <button class="btn btn-sm btn-success install-theme" data-theme-id="{{ $theme['id'] }}"
                            data-theme-name="{{ $theme['name'] }}">
                            <i class="fa fa-download"></i> {{ __('core::translation.install') }}
                        </button>
                    @else
                        <a href="{{ $theme['demo_url'] ?? '#' }}" class="btn btn-sm btn-primary" target="_blank">
                            <i class="fa fa-eye"></i> {{ __('core::translation.view_demo') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="marketplace-modal-{{ $theme['id'] }}" tabindex="-1"
        aria-labelledby="marketplace-modal-{{ $theme['id'] }}Label" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="marketplace-modal-{{ $theme['id'] }}Label">{{ $theme['title'] }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Thumbnail -->
                        <div class="col-md-4 text-center mb-3">
                            <img src="{{ $theme['thumbnail'] ?? 'https://placehold.co/400x300?text=' . urlencode($theme['title']) }}"
                                alt="{{ $theme['title'] }}" class="img-fluid rounded shadow">
                        </div>

                        <!-- Info -->
                        <div class="col-md-8">
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">{{ __('core::translation.description') }}</h6>
                                <p>{{ $theme['description'] }}</p>
                            </div>

                            <div class="mb-3">
                                <h6 class="text-muted mb-2">{{ __('core::translation.information') }}</h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td><strong>{{ __('core::translation.package_name') }}:</strong></td>
                                        <td><code>{{ $theme['name'] }}</code></td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('core::translation.price') }}:</strong></td>
                                        <td>
                                            @if ($theme['is_free'])
                                                <span class="badge badge-success">{{ __('core::translation.free') }}</span>
                                            @else
                                                ${{ number_format($theme['price'], 2) }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('core::translation.rating') }}:</strong></td>
                                        <td>
                                            <i class="fa fa-star text-warning"></i>
                                            {{ number_format($theme['rating'], 1) }}
                                            ({{ $theme['total_reviews'] }} {{ __('core::translation.reviews') }})
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('core::translation.downloads') }}:</strong></td>
                                        <td>{{ number_format($theme['sales']) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('core::translation.created_at') }}:</strong></td>
                                        <td>{{ \Carbon\Carbon::parse($theme['created_at'])->format('M d, Y') }}</td>
                                    </tr>
                                </table>
                            </div>

                            @if ($theme['demo_url'])
                                <div class="mb-2">
                                    <a href="{{ $theme['demo_url'] }}" class="btn btn-primary btn-sm" target="_blank">
                                        <i class="fa fa-external-link-alt"></i> {{ __('core::translation.view_demo') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @if ($theme['is_free'])
                        <button type="button" class="btn btn-success install-theme" data-theme-id="{{ $theme['id'] }}"
                            data-theme-name="{{ $theme['name'] }}">
                            <i class="fa fa-download"></i> {{ __('core::translation.install') }}
                        </button>
                    @else
                        <a href="#" class="btn btn-primary">
                            <i class="fa fa-shopping-cart"></i> {{ __('core::translation.buy_now') }}
                        </a>
                    @endif
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        {{ __('core::translation.close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
