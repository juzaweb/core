@extends('core::layouts.admin')

@section('content')
    <form action="{{ $action }}" class="form-ajax" method="post">
        @if($model->exists)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-12">
                <a href="{{ admin_url('pages') }}" class="btn btn-warning">
                    <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                </a>

                <button class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ __('Save') }}
                </button>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Pages') }}</h3>
                    </div>
                    <div class="card-body">
                        {{ Field::text($model, "{$locale}[title]", ['label' => __('Title'), 'value' => $model->title]) }}

                        {{ Field::editor($model, "{$locale}[content]", ['label' => __('Content'), 'value' => $model->content]) }}
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        {{ Field::select($model, 'status', ['label' => __('Status')])
                            ->dropDownList(
                                \Juzaweb\Core\Models\Enums\PageStatus::all()
                            ) }}
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        {{ Field::image($model, "{$locale}[thumbnail]", ['label' => __('Thumbnail'), 'value' => $model->thumbnail]) }}
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        @php
                            $languages = \Juzaweb\Core\Models\Language::languages();
                        @endphp
                        <div class="form-group">
                            <label for="locale">{{ __('Language') }}</label>
                            <select name="locale" id="locale" class="form-control select-language">
                                @foreach($languages as $key => $language)
                                    <option value="{{ $key }}" @selected($locale == $key)>
                                        {{ $language->name }} ({{ $language->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')

@endsection
