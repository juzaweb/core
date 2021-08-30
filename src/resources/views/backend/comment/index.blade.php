@extends('juzaweb::layouts.backend')

@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="float-right">
                <div class="btn-group">

                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <form method="get" class="form-inline" id="form-search">

                <div class="form-group mb-2 mr-1">
                    <label for="search" class="sr-only">@lang('juzaweb::app.search')</label>
                    <input name="search" type="text" id="search" class="form-control" placeholder="@lang('juzaweb::app.search')" autocomplete="off">
                </div>

                <div class="form-group mb-2 mr-1">
                    <label for="status" class="sr-only">@lang('juzaweb::app.status')</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">--- @lang('juzaweb::app.status') ---</option>
                        <option value="approved">@lang('juzaweb::app.approved')</option>
                        <option value="deny">@lang('juzaweb::app.deny')</option>
                        <option value="pending">@lang('juzaweb::app.pending')</option>
                    </select>
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
                    <th data-field="author">@lang('juzaweb::app.author')</th>
                    <th data-width="30%" data-field="content">@lang('juzaweb::app.content')</th>
                    <th data-width="15%" data-field="post">@lang('juzaweb::app.post')</th>
                    <th data-width="10%" data-field="status" data-align="center" data-formatter="status_formatter">@lang('juzaweb::app.status')</th>
                    <th data-width="15%" data-field="created_at">@lang('juzaweb::app.created_at')</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function status_formatter(value, row, index) {
            if (value == 1) {
                return `<span class="text-success">${juzaweb.lang.approved}</span>`;
            }
            return `<span class="text-danger">${juzaweb.lang.deny}</span>`;
        }

        var table = new JuzawebTable({
            url: '{{ route("admin.{$postTypeSingular}.comments.get-data") }}',
            action_url: '{{ route("admin.{$postTypeSingular}.comments.bulk-actions") }}'
        });
    </script>
@endsection