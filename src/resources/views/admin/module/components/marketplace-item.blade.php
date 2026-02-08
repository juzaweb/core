<div class="card">
    <div class="height-200 d-flex flex-column position-relative">
        @if ($module['is_free'])
            <span class="badge badge-success price-badge">{{ __('core::translation.free') }}</span>
        @else
            <span class="badge badge-primary price-badge">${{ number_format($module['price'], 2) }}</span>
        @endif

        <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
            data-src="{{ $module['thumbnail'] ?? 'https://placehold.co/590x300?text=' . urlencode($module['title']) }}"
            alt="{{ $module['title'] }}" class="lazyload w-100 h-100">
    </div>

    <div class="card card-bottom card-borderless mb-0">
        <div class="card-header border-bottom-0">
            <div class="d-flex flex-column">
                <div class="text-dark text-uppercase font-weight-bold mb-2">
                    {{ $module['title'] }}
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="text-muted small">
                        <i class="fa fa-star text-warning"></i> {{ number_format($module['rating'], 1) }}
                        ({{ $module['total_reviews'] }})
                    </div>
                    <div class="text-muted small">
                        <i class="fa fa-download"></i> {{ number_format($module['sales']) }}
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="javascript:void(0)" class="btn btn-sm btn-info text-white" data-toggle="modal"
                        data-target="#marketplace-modal-{{ $module['id'] }}">
                        <i class="fa fa-info-circle"></i> {{ __('core::translation.details') }}
                    </a>

                    @php
                        $isInstalled = in_array($module['name'], $installedModules ?? []);
                    @endphp

                    @if ($isInstalled)
                        <button type="button" class="btn btn-sm btn-secondary" disabled>
                            <i class="fa fa-check"></i> {{ __('core::translation.installed') }}
                        </button>
                    @else
                        <a href="javascript:void(0)" class="btn btn-sm btn-success" data-toggle="modal"
                            data-target="#install-modal-{{ $module['id'] }}">
                            <i class="fa fa-download"></i> {{ __('core::translation.install') }}
                        </a>
                    @endif

                    @if (isset($module['demo_url']))
                        <a href="{{ $module['demo_url'] }}" class="btn btn-sm btn-primary" target="_blank">
                            <i class="fa fa-eye"></i> {{ __('core::translation.view_demo') }}
                        </a>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="marketplace-modal-{{ $module['id'] }}" tabindex="-1"
        aria-labelledby="marketplace-modal-{{ $module['id'] }}Label" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="marketplace-modal-{{ $module['id'] }}Label">{{ $module['title'] }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Thumbnail -->
                        <div class="col-md-4 text-center mb-3">
                            <img src="{{ $module['thumbnail'] ?? 'https://placehold.co/400x300?text=' . urlencode($module['title']) }}"
                                alt="{{ $module['title'] }}" class="img-fluid rounded shadow mb-3">

                            <a href="{{ $module['url'] ?? '' }}"
                                class="btn btn-primary btn-sm btn-block" target="_blank">
                                <i class="fa fa-external-link-alt"></i> {{ __('core::translation.view_more') }}
                            </a>
                        </div>

                        <!-- Info -->
                        <div class="col-md-8">
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">{{ __('core::translation.description') }}</h6>
                                <p>{{ $module['description'] }}</p>
                            </div>

                            <div class="mb-3">
                                <h6 class="text-muted mb-2">{{ __('core::translation.information') }}</h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td><strong>{{ __('core::translation.package_name') }}:</strong></td>
                                        <td><code>{{ $module['name'] }}</code></td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('core::translation.price') }}:</strong></td>
                                        <td>
                                            @if ($module['is_free'])
                                                <span class="badge badge-success">{{ __('core::translation.free') }}</span>
                                            @else
                                                ${{ number_format($module['price'], 2) }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('core::translation.rating') }}:</strong></td>
                                        <td>
                                            <i class="fa fa-star text-warning"></i>
                                            {{ number_format($module['rating'], 1) }}
                                            ({{ $module['total_reviews'] }} {{ __('core::translation.reviews') }})
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('core::translation.downloads') }}:</strong></td>
                                        <td>{{ number_format($module['sales']) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('core::translation.created_at') }}:</strong></td>
                                        <td>{{ \Carbon\Carbon::parse($module['created_at'])->format('M d, Y') }}</td>
                                    </tr>
                                </table>
                            </div>

                            @if (isset($module['demo_url']))
                                <div class="mb-2">
                                    <a href="{{ $module['demo_url'] }}" class="btn btn-primary btn-sm" target="_blank">
                                        <i class="fa fa-external-link-alt"></i> {{ __('core::translation.view_demo') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @php
                        $isInstalled = in_array($module['name'], $installedModules ?? []);
                    @endphp

                    @if ($isInstalled)
                        <button type="button" class="btn btn-secondary" disabled>
                            <i class="fa fa-check"></i> {{ __('core::translation.installed') }}
                        </button>
                    @else
                        <a href="javascript:void(0)" class="btn btn-success" data-dismiss="modal" data-toggle="modal"
                            data-target="#install-modal-{{ $module['id'] }}">
                            <i class="fa fa-download"></i> {{ __('core::translation.install') }}
                        </a>
                    @endif

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        {{ __('core::translation.close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Installation Guide Modal -->
    <div class="modal fade" id="install-modal-{{ $module['id'] }}" tabindex="-1"
        aria-labelledby="install-modal-{{ $module['id'] }}Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="install-modal-{{ $module['id'] }}Label">
                        <i class="fa fa-download"></i> {{ __('core::translation.install_module') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6 class="mb-3">{{ __('core::translation.installation_instructions') }}</h6>
                    <p class="text-muted">{{ __('core::translation.run_command_in_terminal') }}:</p>

                    <div class="alert alert-secondary d-flex justify-content-between align-items-center mb-0">
                        <code class="text-dark mb-0" id="install-command-{{ $module['id'] }}">php artisan module:install {{ $module['name'] }}</code>
                        <button type="button" class="btn btn-sm btn-outline-primary ml-2 copy-command"
                            data-command="php artisan module:install {{ $module['name'] }}">
                            <i class="fa fa-copy"></i> {{ __('core::translation.copy') }}
                        </button>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="fa fa-info-circle"></i>
                        <small>{{ __('core::translation.install_module_note') }}</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        {{ __('core::translation.close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
