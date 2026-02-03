@extends('core::layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @can('pages.create')
                <a href="{{ admin_url('pages/create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> {{ __('core::translation.add_page') }}
                </a>
            @endcan
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            @component('core::components.datatables.filters')
                <div class="col-md-3 jw-datatable_filters">
                    {{ Field::select(__('core::translation.status'), 'status')->dropDownList(
                        [
                            '' => __('core::translation.all'),
                            ...\Juzaweb\Modules\Core\Enums\PageStatus::all(),
                        ]
                    )->selected(request('status')) }}
                </div>
            @endcomponent
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('core::translation.pages') }}</h3>
                </div>
                <div class="card-body">
                    {{ $dataTable->table() }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="quick-edit-modal" tabindex="-1" role="dialog" aria-labelledby="quick-edit-modal-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="quick-edit-form" method="post">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="quick-edit-modal-label">{{ __('core::translation.quick_edit') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="quick-edit-id">

                        {{ Field::text(__('core::translation.title'), 'title', ['id' => 'quick-edit-title']) }}

                        {{ Field::select(__('core::translation.status'), 'status', ['id' => 'quick-edit-status'])->options(\Juzaweb\Modules\Core\Enums\PageStatus::all()) }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('core::translation.close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('core::translation.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{ $dataTable->scripts(null, ['nonce' => csp_script_nonce()]) }}

    <script nonce="{{ csp_script_nonce() }}">
        $(document).on('click', '.datatables-row-action[data-action="quick-edit"]', function () {
            let id = $(this).data('id');
            let $modal = $('#quick-edit-modal');

            $.ajax({
                url: "{{ admin_url('pages') }}/" + id + "/modal-data",
                type: 'GET',
                success: function (response) {
                    $('#quick-edit-id').val(id);
                    $('#quick-edit-title').val(response.data.title);
                    $('#quick-edit-status').val(response.data.status).trigger('change');
                    $modal.modal('show');
                },
                error: function (xhr) {
                    show_message(xhr);
                }
            });
        });

        $('#quick-edit-form').on('submit', function (e) {
            e.preventDefault();
            let id = $('#quick-edit-id').val();
            let formData = $(this).serialize();

            $.ajax({
                url: "{{ admin_url('pages') }}/" + id + "/quick-update",
                type: 'POST',
                data: formData,
                success: function (response) {
                    $('#quick-edit-modal').modal('hide');
                    show_message(response);
                    $('.dataTable').DataTable().ajax.reload(null, false);
                },
                error: function (xhr) {
                    show_message(xhr);
                }
            });
        });
    </script>
@endsection
