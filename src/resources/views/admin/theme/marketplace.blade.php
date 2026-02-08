@extends('core::layouts.admin')

@section('head')
    <style>
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

        // Copy command to clipboard
        $(document).on('click', '.copy-command', function() {
            const command = $(this).data('command');
            const btn = $(this);
            const originalHtml = btn.html();

            // Copy to clipboard
            navigator.clipboard.writeText(command).then(function() {
                btn.html('<i class="fa fa-check"></i> {{ __('core::translation.copied') }}');
                setTimeout(function() {
                    btn.html(originalHtml);
                }, 2000);
            }).catch(function(err) {
                console.error('Failed to copy:', err);
            });
        });
    </script>
@endsection
