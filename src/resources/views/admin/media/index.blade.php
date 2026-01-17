@extends('core::layouts.admin')

@section('content')
    <div id="media-container" data-folder-id="{{ $folderId ?? '' }}">
        <div class="row mb-2">
            <div class="col-md-8">
                <form action="" method="get" class="form-inline">
                    <input type="text" class="form-control w-25" name="q"
                        placeholder="{{ __('core::translation.search_by_name') }}" autocomplete="off"
                        value="{{ request('q') }}">

                    {{-- <select name="mime" class="form-control w-25 ml-1">
                        <option value="">All media items</option>
                        <option value="image">Images</option>
                        <option value="audio">Audio</option>
                        <option value="video">Video</option>
                        <option value="application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-word.document.macroEnabled.12,application/vnd.ms-word.template.macroEnabled.12,application/vnd.oasis.opendocument.text,application/vnd.apple.pages,application/pdf,application/vnd.ms-xpsdocument,application/oxps,application/rtf,application/wordperfect,application/octet-stream">Documents</option>
                        <option value="application/vnd.apple.numbers,application/vnd.oasis.opendocument.spreadsheet,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel.sheet.macroEnabled.12,application/vnd.ms-excel.sheet.binary.macroEnabled.12">Spreadsheets</option>
                        <option value="application/x-gzip,application/rar,application/x-tar,application/zip,application/x-7z-compressed">Archives</option>
                        <option value="unattached">Unattached</option>
                        <option value="mine">Mine</option>
                    </select> --}}

                    <button type="submit" class="btn btn-primary ml-1">{{ __('core::translation.search') }}</button>
                </form>
            </div>

            <div class="col-md-4">
                <div class="btn-group float-right">
                    <a href="javascript:void(0)" class="btn btn-success" data-toggle="modal"
                        data-target="#add-folder-modal"><i class="fas fa-plus"></i>
                        {{ __('core::translation.add_folder') }}</a>
                    <a href="javascript:void(0)" class="btn btn-success" data-toggle="modal" data-target="#upload-modal"><i
                            class="fas fa-cloud-upload-alt"></i> {{ __('core::translation.upload') }}</a>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-12" id="media-list-container">
                <x-card>
                    <div class="list-media">
                        <ul class="media-list row list-unstyled">
                            @foreach ($mediaFiles as $item)
                                @component('core::admin.media.components.item', ['item' => $item])
                                @endcomponent
                            @endforeach
                        </ul>
                        @if ($mediaFiles->isEmpty())
                            <p class="text-center">{{ __('core::translation.no_files_found') }}</p>
                        @endif
                        <div class="loading-indicator text-center py-3" style="display: none;">
                            <i class="fas fa-spinner fa-spin fa-2x"></i>
                            <p>{{ __('core::translation.loading_more_items') }}</p>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>

        <!-- Context Menu -->
        <div id="context-menu" class="context-menu" style="display: none;">
            <ul>
                <li class="context-menu-item" data-action="view-detail">
                    <i class="fas fa-info-circle"></i> {{ __('core::translation.view_detail') }}
                </li>
                <li class="context-menu-item" data-action="delete">
                    <i class="fas fa-trash text-danger"></i> <span
                        class="text-danger">{{ __('core::translation.delete') }}</span>
                </li>
            </ul>
        </div>
    </div>
@endsection

@section('scripts')
    <template id="media-detail-template">
        <div class="box-image text-center mb-3">
            <img src="{url}" alt="" class="preview-image img-fluid" style="max-height: 300px;">
        </div>

        <div class="mt-2 mb-3 text-center">
            <a href="{{ str_replace('__ID__', '{id}', route('admin.media.download', ['public', '__ID__'])) }}"
                class="btn btn-secondary">
                <i class="fa fa-download"></i> {{ __('core::translation.download') }}
            </a>

            <a href="javascript:void(0)" class="btn btn-danger delete-file" data-id="{id}" data-is_file="{is_file}"
                data-name="{name}"><i class="fa fa-trash"></i> {{ __('core::translation.delete') }}</a>
        </div>

        <form action="{{ str_replace('__ID__', '{id}', route('admin.media.update', ['__ID__'])) }}" method="post"
            class="form-ajax">
            @method('put')
            <input type="hidden" name="is_file" value="{is_file}">

            {{ Field::text(__('core::translation.name'), 'name', ['value' => '{name}']) }}

            {{ Field::text(__('core::translation.url'), 'url', ['value' => '{url}', 'disabled' => true]) }}

            <table class="table">
                <tbody>
                    <tr>
                        <td>{{ __('core::translation.extension') }}</td>
                        <td>{extension}</td>
                    </tr>

                    <tr>
                        <td>{{ __('core::translation.size') }}</td>
                        <td>{size}</td>
                    </tr>
                    <tr>
                        <td>{{ __('core::translation.last_update') }}</td>
                        <td>{updated}</td>
                    </tr>
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary mb-2">{{ __('core::translation.save') }}</button>
        </form>
    </template>

    <div class="modal fade" id="media-preview-modal" tabindex="-1" role="dialog" aria-labelledby="mediaPreviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediaPreviewModalLabel">{{ __('core::translation.file_details') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Content will be injected here -->
                </div>
            </div>
        </div>
    </div>

    @include('core::admin.media.components.add_modal')

    @include('core::admin.media.components.upload_modal')

    <script type="text/javascript" nonce="{{ csp_script_nonce() }}">
        Dropzone.autoDiscover = false;

        // Initialize infinite scroll data
        window.mediaInfiniteScroll = {
            currentPage: {{ $mediaFiles->currentPage() }},
            lastPage: {{ $mediaFiles->lastPage() }},
            hasMorePages: {{ $mediaFiles->hasMorePages() ? 'true' : 'false' }}
        };

        $(function() {
            new Dropzone("#uploadForm", {
                paramName: "upload",
                uploadMultiple: false,
                parallelUploads: 5,
                timeout: 0,
                dictDefaultMessage: "{{ __('core::browser.message-drop') }}",
                init: function() {
                    this.on('success', function(file, response) {
                        window.location.reload();
                    });
                },
                headers: {
                    'Authorization': "Bearer {{ csrf_token() }}"
                },
                acceptedFiles: "{{ implode(',', $mimeTypes) }}",
                maxFilesize: parseInt("{{ $maxSize }}"),
                chunking: true,
                forceChunking: true,
                chunkSize: 1048576,
            });
        });

        function add_folder_success(form, response) {
            if (response.success ?? false) {
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            }
        }
    </script>
@endsection
