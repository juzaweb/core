@extends('juzaweb::layouts.backend')

@section('content')
    <div class="row mb-2">
        <div class="col-md-3">
            <form method="post" class="form-inline">
                <select name="bulk_actions" class="form-control w-60 mb-2 mr-1">
                    <option value="">@lang('juzaweb::app.bulk_actions')</option>
                    <option value="delete">@lang('juzaweb::app.delete')</option>
                </select>

                <button type="submit" class="btn btn-primary mb-2" id="apply-action">@lang('juzaweb::app.apply')</button>
            </form>
        </div>

        <div class="col-md-9">
            <form method="get" class="form-inline" id="form-search">
                <div class="form-group mb-2 mr-1">
                    <label for="search" class="sr-only">@lang('juzaweb::app.search')</label>
                    <input name="search" type="text" id="search" class="form-control" placeholder="@lang('juzaweb::app.search')" autocomplete="off">
                </div>

                <button type="submit" class="btn btn-primary mb-2"><i class="fa fa-search"></i> @lang('juzaweb::app.search')</button>
            </form>
        </div>
    </div>

    <div class="table-responsive mb-5">
        <table class="table juzaweb-table">
            <thead>
                <tr>
                    <th data-width="3%" data-field="state" data-checkbox="true"></th>
                    <th data-field="code" data-sortable="true" data-formatter="name_formatter">@lang('juzaweb::app.code')</th>
                    <th data-width="20%" data-field="subject">@lang('juzaweb::app.subject')</th>
                    <th data-width="15%" data-sortable="true" data-field="created_at">@lang('juzaweb::app.created_at')</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.code +'</a>';
        }

        var table = new JuzawebTable({
            url: '{{ route('admin.email-template.get-data') }}',
            action_url: '{{ route('admin.email-template.bulk-actions') }}',
        });
    </script>
@endsection