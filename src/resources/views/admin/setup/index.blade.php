@extends('core::layouts.none')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('core::translation.welcome_to_your_new_website') }}</h3>
                </div>
                <div class="card-body">
                    <div id="setup-content">
                        <p class="lead">
                            {{ __('core::translation.your_website_is_almost_ready_just_wait_a_little_longer') }}</p>
                    </div>

                    <div id="setup-loading" style="display: none;" class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">{{ __('core::translation.loading') }}</span>
                        </div>
                        <p class="mt-3">{{ __('core::translation.setting_up_your_website_please_wait') }}</p>
                    </div>

                    <div id="setup-success" style="display: none;">
                        <div class="alert alert-success">
                            <h5><i class="icon fas fa-check"></i> {{ __('core::translation.success') }}</h5>
                            <p>{{ __('core::translation.your_website_has_been_set_up_successfully_redirecting_to_dashboard') }}
                            </p>
                        </div>
                    </div>

                    <div id="setup-error" style="display: none;">
                        <div class="alert alert-danger">
                            <h5><i class="icon fas fa-ban"></i> {{ __('core::translation.error') }}</h5>
                            <p id="setup-error-message"></p>
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-primary" id="retry-button">
                                <i class="fas fa-redo"></i> {{ __('core::translation.try_again') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script nonce="{{ csp_script_nonce() }}">
        $(function() {
            function startSetup() {
                $('#setup-content, #setup-error').hide();
                $('#setup-loading').show();

                $.ajax({
                    url: '{{ route('admin.setup.process') }}',
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        $('#setup-loading').hide();

                        if (response.status) {
                            $('#setup-success').show();

                            // Redirect to dashboard after 2 seconds
                            setTimeout(function() {
                                window.location.href = response.redirect;
                            }, 2000);
                        } else {
                            $('#setup-error-message').text(response.message ||
                                '{{ __('core::translation.an_error_occurred_during_setup') }}');
                            $('#setup-error').show();
                        }
                    },
                    error: function(xhr) {
                        $('#setup-loading').hide();

                        let errorMessage =
                            '{{ __('core::translation.an_error_occurred_during_setup') }}';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        $('#setup-error-message').text(errorMessage);
                        $('#setup-error').show();
                    }
                });
            }

            $('#retry-button').on('click', function() {
                startSetup();
            });

            // Start the setup process on page load
            startSetup();
        });
    </script>
@endsection
