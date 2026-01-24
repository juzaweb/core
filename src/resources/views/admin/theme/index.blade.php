@extends('core::layouts.admin')

@section('head')
    <style>
        #theme-list .theme-list-item .card-bottom {
            position: absolute;
            background: rgb(255 255 255 / 88%);
            width: 100%;
            bottom: 0;
            display: none;
        }

        #theme-list .theme-list-item:hover .card-bottom {
            display: block;
        }

        #theme-list .theme-list-item .height-200 {
            height: 200px;
        }
    </style>
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="float-right">
                <a href="javascript:void(0)" class="btn btn-success" data-toggle="modal" data-target="#upload-theme-modal">
                    <i class="fas fa-cloud-upload-alt"></i> {{ __('core::translation.upload_theme') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row" id="theme-list">
        <div class="col-md-4 p-2 theme-list-item">
            @component('core::admin.theme.components.theme-item', ['theme' => $currentTheme, 'active' => true])
            @endcomponent
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Upload Theme Modal -->
    <div class="modal fade" id="upload-theme-modal" tabindex="-1" role="dialog" aria-labelledby="upload-theme-modal-label"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="upload-theme-modal-label">
                        <i class="fa fa-cloud-upload-alt"></i> {{ __('core::translation.upload_theme') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="{{ __('core::translation.close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="upload-container p-2">
                        <form action="{{ route('upload.temp') }}" role="form" id="uploadThemeForm" name="uploadThemeForm"
                            method="post" class="dropzone" enctype='multipart/form-data'>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="dz-message text-center">
                                <i class="fa fa-cloud-upload-alt fa-4x mb-3 text-muted"></i>
                                <h4>{{ __('core::browser.message-drop') }}</h4>
                                <p class="text-muted">{{ __('core::browser.message-choose') }}</p>
                                <p class="text-info"><small>{{ __('core::translation.accepted_file_types') }}: .zip</small>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" nonce="{{ csp_script_nonce() }}">
        Dropzone.autoDiscover = false;

        $(function() {
            // Initialize Dropzone for theme upload
            new Dropzone("#uploadThemeForm", {
                paramName: "file",
                uploadMultiple: false,
                parallelUploads: 1,
                timeout: 0,
                maxFiles: 1,
                acceptedFiles: ".zip",
                maxFilesize: 50, // 50MB
                dictDefaultMessage: "{{ __('core::browser.message-drop') }}",
                chunking: true,
                forceChunking: true,
                chunkSize: 1000000, // 1MB chunks
                init: function() {
                    this.on('success', function(file, response) {
                        if (response.status && response.path) {
                            show_message('{{ __('core::translation.upload_success') }}',
                                'success');
                            $('#upload-theme-modal').modal('hide');
                            // You can add additional logic here to process the uploaded theme
                            console.log('Theme uploaded to:', response.path, 'on disk:',
                                response.disk);
                        }
                    });
                    this.on('error', function(file, errorMessage) {
                        show_message(errorMessage.message || errorMessage, 'error');
                    });
                },
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });

            // Theme list logic
            let pageSize = 12;
            let offset = 0;
            let total = 0;
            let page = 1;

            function loadData() {
                let jqxhr = $.ajax({
                    type: this.method,
                    url: '{{ route('admin.themes.get-data') }}',
                    dataType: 'json',
                    cache: false,
                    async: false,
                    data: {
                        page: page,
                        limit: pageSize,
                    }
                });

                let response = jqxhr.responseJSON;
                total = response.total;

                if (response.html) {
                    $('#theme-list').append(response.html);
                }
            }

            loadData();

            $('#theme-list').on('click', '.active-theme', function() {
                let btn = $(this);
                let icon = btn.find('i').attr('class');
                let theme = btn.data('theme');

                btn.find('i').attr('class', 'fa fa-spinner fa-spin');
                btn.prop("disabled", true);

                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.themes.activate') }}",
                    dataType: 'json',
                    data: {
                        theme: theme
                    }
                }).done(function(response) {
                    btn.find('i').attr('class', icon);
                    btn.prop("disabled", false);

                    if (response.status === false) {
                        show_message(response.data.message);
                        return false;
                    }

                    window.location.reload();
                    return false;
                }).fail(function(response) {
                    btn.find('i').attr('class', icon);
                    btn.prop("disabled", false);
                    show_message(response);
                    return false;
                });
            });

            $(window).scroll(function() {
                if ($(window).scrollTop() === $(document).height() - $(window).height()) {
                    if (offset + pageSize < total) {
                        page = page + 1;
                        loadData();
                    }
                }
            });
        });
    </script>
@endsection
