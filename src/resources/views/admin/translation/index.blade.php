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
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
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
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                ]
            });
        });
    </script>
@endsection
