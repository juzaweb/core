@extends('core::layouts.admin')

@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="float-right">
                @if (config('modules.upload_enabled'))
                    {{--<a href="javascript:void(0)" class="btn btn-success" data-toggle="modal" data-target="#upload-theme-modal">
                        <i class="fas fa-cloud-upload-alt"></i> {{ __('core::translation.upload_theme') }}
                    </a>--}}

                    @can('modules.create')
                        <a class="btn btn-primary" href="{{ route('admin.modules.marketplace') }}">
                            <i class="fa fa-store"></i> {{ __('core::translation.marketplace') }}
                        </a>
                    @endcan
                @endif
            </div>
        </div>
    </div>

    <div class="row" id="module-list">
        @foreach($modules as $module)
            <div class="col-md-4 col-lg-3 p-2 module-list-item">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $module->get('title', $module->getStudlyName()) }}</h5>
                        <p class="card-text">{{ $module->getDescription() }}</p>
                        <p class="card-text"><small class="text-muted">Version: {{ $module->get('version') }}</small></p>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge badge-{{ $module->isEnabled() ? 'success' : 'secondary' }}">
                                {{ $module->isEnabled() ? __('core::translation.active') : __('core::translation.inactive') }}
                            </span>

                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input toggle-module"
                                       id="module-toggle-{{ $module->getLowerName() }}"
                                       data-module="{{ $module->getLowerName() }}"
                                       {{ $module->isEnabled() ? 'checked' : '' }}>
                                <label class="custom-control-label" for="module-toggle-{{ $module->getLowerName() }}"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" nonce="{{ csp_script_nonce() }}">
        $(function () {
            $('.toggle-module').on('change', function () {
                let checkbox = $(this);
                let module = checkbox.data('module');
                let status = checkbox.is(':checked') ? 1 : 0;

                checkbox.prop('disabled', true);

                if (status === 1) {
                    $.ajax({
                        url: "{{ route('admin.modules.install') }}",
                        method: 'POST',
                        data: {module: module},
                        xhr: function () {
                            let xhr = new XMLHttpRequest();
                            xhr.onprogress = function (e) {
                                console.log(e.currentTarget.response);
                            };
                            return xhr;
                        }
                    }).done(function (response) {
                        // toggleModule(module, status, checkbox);
                    }).fail(function (response) {
                        checkbox.prop('disabled', false);
                        checkbox.prop('checked', !status);
                        show_message(response);
                    });
                    return;
                }

                toggleModule(module, status, checkbox);
            });

            function toggleModule(module, status, checkbox) {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.modules.toggle') }}",
                    dataType: 'json',
                    data: {
                        module: module,
                        status: status
                    }
                }).done(function (response) {
                    checkbox.prop('disabled', false);

                    if (response.status === false) {
                        show_message(response.message);
                        checkbox.prop('checked', !status); // Revert status
                        return false;
                    }

                    show_message(response.message);
                    // setTimeout(function() {
                    //     window.location.reload();
                    // }, 1000);

                }).fail(function (response) {
                    checkbox.prop('disabled', false);
                    checkbox.prop('checked', !status); // Revert status
                    show_message(response);
                    return false;
                });
            }
        });
    </script>
@endsection
