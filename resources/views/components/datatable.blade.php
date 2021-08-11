<div class="row mb-3">
    @if($actions)
        <div class="col-md-4">
            <form method="post" class="form-inline">
                @csrf

                <select name="bulk_actions" class="form-control w-60 mb-2 mr-1">
                    <option value="">@lang('juzaweb::app.bulk_actions')</option>
                    @foreach($actions as $key => $action)
                        <option value="{{ $key }}">{{ $action['label'] ?? strtoupper($key) }}</option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-primary mb-2" id="apply-action">@lang('juzaweb::app.apply')</button>
            </form>
        </div>
    @endif

    <div class="col-md-8">
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
    <table class="table" id="{{ $unique_id }}">
        <thead>
            <tr>
                <th data-width="3%" data-field="state" data-checkbox="true"></th>
                @foreach($columns as $key => $column)
                    <th
                            data-width="{{ $column['width'] ?? 'auto' }}"
                            data-align="{{ $column['align'] ?? 'left' }}"
                            data-field="{{ $key }}">{{ $column['label'] ?? strtoupper($key) }}
                    </th>
                @endforeach
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    var table = new JuzawebTable({
        table: "#{{ $unique_id }}",
        url: '{{ route('admin.datatable.get-data') }}?table={{ urlencode($table) }}',
    });
</script>