@extends('core::layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @can('languages.create')
            <a href="javascript:void(0);" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">
                <i class="fas fa-plus"></i> {{ __('Add Language') }}
            </a>
            @endcan
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Languages') }}</h3>
                </div>
                <div class="card-body">
                    {{ $dataTable->table() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @can('languages.create')
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <form action="" class="form-ajax" method="post" data-success="addLanguageSuccess">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ __('Add new language') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ Field::select(__('Language'), 'code', [])
                            ->dropDownList(collect(config('locales'))->pluck('name', 'code')
                            ->prepend('--- Choose Language ---', '')->toArray())
                        }}

                        {{ Field::text(__('Name'), 'name', ['placeholder' => __('Language Name')]) }}
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-primary">@lang('Save changes')</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('Close')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endcan

    {{ $dataTable->scripts() }}

    <script>
        function addLanguageSuccess(form, res) {
            $('#modal-default').modal('hide');

            $('#jw-datatable').DataTable().ajax.reload();
        }

        $(function () {
            $('#code').on('change', function () {
                const languageName = $(this).find('option:selected').text().trim();
                if (! $(this).val()) {
                    $('#name').val('');
                    return;
                }

                $('#name').val(languageName);
            });
        });
    </script>
@endsection
