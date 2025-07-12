@extends('core::layouts.admin')

@section('content')
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Users') }}</h3>
                </div>
                <div class="card-body">
                    <table id="translations-table" class="table table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th style="width: 30%;">{{ __('Master Value') }}</th>
                                <th>{{ __('Phrase text') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            $('#translations-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.languages.translations.get-data', [$locale]) }}',
                columns: [
                    {data: 'value', name: 'value'},
                    {
                        data: 'trans',
                        name: 'trans',
                        render: function (data, type, row) {
                            data = htmlspecialchars(data);

                            return `<input class="form-control trans-text" type="text" value="${data}" data-key="${row.key}" data-group="${row.group}" data-namespace="${row.namespace}"/>`;
                        }
                    },
                ]
            });

            $(document).on('change', '.trans-text', function (e) {
                e.preventDefault();

                let $this = $(this);
                let key = $this.data('key');
                let group = $this.data('group');
                let namespace = $this.data('namespace');
                let value = $this.val();

                $.ajax({
                    url: '{{ route('admin.languages.translations.update', [$locale]) }}',
                    type: 'PUT',
                    data: {
                        key: key,
                        group: group,
                        namespace: namespace,
                        value: value,
                    },
                    success: function (response) {
                        // Check if the response is successful
                    },
                    error: function () {
                        toastr.error('{{ __('An error occurred while updating the translation.') }}');
                    }
                });
            });
        });
    </script>
@endsection
