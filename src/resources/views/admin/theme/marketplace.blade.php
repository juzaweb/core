@extends('core::layouts.admin')

@section('head')
    <style>
        .nav-tabs {
            border-bottom: 2px solid #dee2e6;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
        }

        .nav-tabs .nav-link:hover {
            border: none;
            color: #007bff;
        }

        .nav-tabs .nav-link.active {
            color: #007bff;
            border: none;
            border-bottom: 3px solid #007bff;
            background-color: transparent;
        }

        #marketplace-list .marketplace-item .card-bottom {
            position: absolute;
            background: rgb(255 255 255 / 88%);
            width: 100%;
            bottom: 0;
            display: none;
        }

        #marketplace-list .marketplace-item:hover .card-bottom {
            display: block;
        }

        #marketplace-list .marketplace-item .height-200 {
            height: 200px;
        }

        .price-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
        }
    </style>
@endsection

@section('content')
    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.themes.index') }}">
                <i class="fa fa-laptop"></i> {{ __('core::translation.installed_themes') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('admin.themes.marketplace') }}">
                <i class="fa fa-store"></i> {{ __('core::translation.marketplace') }}
            </a>
        </li>
    </ul>

    <div class="row mb-3">
        <div class="col-md-12">
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> {{ __('core::translation.marketplace_description') }}
            </div>
        </div>
    </div>

    <div class="row" id="marketplace-list">
        <div class="col-md-12 text-center py-5" id="loading-indicator">
            <i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
            <p class="mt-3">{{ __('core::translation.loading') }}</p>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" nonce="{{ csp_script_nonce() }}">
        $(function() {
            let pageSize = 12;
            let page = 1;
            let total = 0;
            let loading = false;

            function loadData() {
                if (loading) return;
                loading = true;

                $.ajax({
                    type: 'GET',
                    url: '{{ route('admin.themes.marketplace.get-data') }}',
                    dataType: 'json',
                    cache: false,
                    data: {
                        page: page,
                        limit: pageSize,
                    },
                    success: function(response) {
                        loading = false;
                        $('#loading-indicator').hide();

                        console.log('Marketplace Response:', response);

                        if (response.success) {
                            total = response.total || 0;

                            if (response.html && response.html.trim() !== '') {
                                $('#marketplace-list').append(response.html);
                            } else if (page === 1) {
                                // No themes found on first page
                                $('#marketplace-list').html(
                                    '<div class="col-md-12"><div class="alert alert-warning">' +
                                    '<i class="fa fa-exclamation-triangle"></i> ' +
                                    '{{ __('core::translation.no_themes_found') }}' +
                                    '</div></div>'
                                );
                            }
                        } else {
                            if (page === 1) {
                                $('#marketplace-list').html(
                                    '<div class="col-md-12"><div class="alert alert-warning">' +
                                    '<i class="fa fa-exclamation-triangle"></i> ' +
                                    (response.message || '{{ __('core::translation.no_themes_found') }}') +
                                    '</div></div>'
                                );
                            }
                        }
                    },
                    error: function(xhr) {
                        loading = false;
                        $('#loading-indicator').hide();

                        if (page === 1) {
                            $('#marketplace-list').html(
                                '<div class="col-md-12"><div class="alert alert-danger">' +
                                '<i class="fa fa-exclamation-circle"></i> ' +
                                '{{ __('core::translation.failed_to_load_marketplace') }}' +
                                '</div></div>'
                            );
                        }
                    }
                });
            }

            // Load initial data
            loadData();

            // Infinite scroll
            $(window).scroll(function() {
                if ($(window).scrollTop() >= $(document).height() - $(window).height() - 100) {
                    if (page * pageSize < total && !loading) {
                        page = page + 1;
                        loadData();
                    }
                }
            });
        });
    </script>
@endsection
