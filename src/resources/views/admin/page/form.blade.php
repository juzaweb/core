@extends('core::layouts.admin')

@section('content')
    <form action="{{ $action }}" class="form-ajax" method="post">
        @if($model->exists)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-12">
                <a href="{{ admin_url('pages') }}" class="btn btn-warning">
                    <i class="fas fa-arrow-left"></i> {{ __('core::translation.back') }}
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ __('core::translation.save') }}
                </button>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('core::translation.pages') }}</h3>
                    </div>
                    <div class="card-body">
                        {{ Field::text($model, "title", ['label' => __('core::translation.title'), 'value' => $model->title]) }}

                        @if(!isset($template) || !$template->blocks)
                            {{ Field::editor($model, "content", ['label' => __('core::translation.content'), 'value' => $model->content]) }}
                        @endif
                    </div>
                </div>

                @if($template && $template->blocks)
                    @include('core::admin.page.components.blocks.block')
                @endif

                {{--<x-seo-meta :model="$model" :locale="$locale"/>--}}
            </div>

            <div class="col-md-3">
                <x-language-card :label="$model" :locale="$locale"/>

                <div class="card">
                    <div class="card-body">
                        {{ Field::select($model, 'status', ['label' => __('core::translation.status')])
                            ->dropDownList(
                                \Juzaweb\Modules\Core\Enums\PageStatus::all()
                            ) }}
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        {{ Field::select($model, 'template', ['label' => __('core::translation.template')])
                            ->dropDownList(
                                [
                                    '' => __('core::translation.select_template'),
                                    ...$templates,
                                ]
                            ) }}

                        {{ Field::checkbox(__('core::translation.set_as_home_page'), 'is_home', [
                            'value' => (theme_setting('home_page') && theme_setting('home_page') == $model->id) ? 1 : 0,
                        ]) }}
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        {{ Field::image($model, "thumbnail", ['label' => __('core::translation.thumbnail'), 'value' => $model->thumbnail]) }}
                    </div>
                </div>

            </div>
        </div>
    </form>
@endsection
