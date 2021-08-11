@extends('juzaweb::layouts.backend')

@section('content')

    @component('juzaweb::components.form_resource', [
        'method' => $model->id ? 'put' : 'post',
        'action' =>  $model->id ?
            route('admin.posts.update', [$model->id]) :
            route('admin.posts.store')
    ])
        <div class="row">
            <div class="col-md-8">

                <div class="form-group">
                    <label class="col-form-label" for="title">@lang('juzaweb::app.title')</label>
                    <input type="text" name="title" class="form-control" id="title" value="{{ $model->title }}" autocomplete="off" required>
                </div>

                @include('juzaweb::components.form_ckeditor', [
                    'name' => 'content',
                    'value' => $model->content,
                ])

                @component('juzaweb::components.form_select', [
                    'label' => trans('juzaweb::app.status'),
                    'name' => 'status',
                    'value' => $model->status,
                    'options' => [
                        'public' => trans('juzaweb::app.public'),
                        'private' => trans('juzaweb::app.private'),
                        'draft' => trans('juzaweb::app.draft'),
                    ],
                ])
                @endcomponent

                @do_action('post_type.'. $postType .'.form.left')
                {{--@include('juzaweb::backend.seo_form')--}}
            </div>

            <div class="col-md-4">
                @component('juzaweb::components.form_image', [
                    'label' => trans('juzaweb::app.thumbnail'),
                    'name' => 'thumbnail',
                    'value' => $model->thumbnail,
                ])@endcomponent

                @do_action('post_type.'. $postType .'.form.rigth', $model)
            </div>
        </div>
    @endcomponent

@endsection
