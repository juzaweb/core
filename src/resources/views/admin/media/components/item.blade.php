<li class="media-item col-6 col-md-2 col-lg-2 mb-1" title="{{ $item->name }}">
    <a href="{{ $item->isDirectory() ? route('admin.media.folder', [$item->id]) : 'javascript:void(0)' }}"
        class="media-item-info @if ($item->isFile()) media-file-item @endif" data-id="{{ $item->id }}">
        @php
            $arr = $item->toArray();
            $arr['url'] = $item->path ? get_full_url(upload_url($item->path), url('/')) : '';
            $arr['updated'] = $item->updated_at?->toUserTimezone()->format('d/m/Y H:i');
            $arr['size'] = format_size_units($item->size);
            $arr['is_file'] = $item->isFile();
        @endphp
        <textarea class="d-none item-info">@json($arr)</textarea>
        <div class="attachment-preview">
            <div class="thumbnail">
                <div class="centered">
                    @if ($item->isDirectory())
                        <div class="file-icon-wrapper text-center p-3">
                            <i class="fa fa-folder fa-4x text-warning"></i>
                        </div>
                    @else
                        @if ($item->isImage())
                            <img class="lazyload"
                                src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                data-src="{{ proxy_image(upload_url($item->path), 150, 150) }}"
                                alt="{{ $item->name }}" />
                        @elseif($item->isVideo())
                            <div class="file-icon-wrapper text-center p-3">
                                <i class="fa fa-file-video fa-4x text-danger"></i>
                                <p class="mt-2 mb-0 small text-muted">{{ strtoupper($item->extension) }}</p>
                            </div>
                        @elseif($item->isAudio())
                            <div class="file-icon-wrapper text-center p-3">
                                <i class="fa fa-file-audio fa-4x text-info"></i>
                                <p class="mt-2 mb-0 small text-muted">{{ strtoupper($item->extension) }}</p>
                            </div>
                        @elseif($item->isDocument())
                            <div class="file-icon-wrapper text-center p-3">
                                @if (in_array($item->extension, ['pdf']))
                                    <i class="fa fa-file-pdf fa-4x text-danger"></i>
                                @else
                                    <i class="fa fa-file-alt fa-4x text-primary"></i>
                                @endif
                                <p class="mt-2 mb-0 small text-muted">{{ strtoupper($item->extension) }}</p>
                            </div>
                        @elseif(in_array($item->extension, ['zip', '7z', 'rar', 'tar', 'gz']))
                            <div class="file-icon-wrapper text-center p-3">
                                <i class="fa fa-file-archive fa-4x text-warning"></i>
                                <p class="mt-2 mb-0 small text-muted">{{ strtoupper($item->extension) }}</p>
                            </div>
                        @else
                            <div class="file-icon-wrapper text-center p-3">
                                <i class="fa fa-file fa-4x text-secondary"></i>
                                <p class="mt-2 mb-0 small text-muted">{{ strtoupper($item->extension) }}</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        <div class="media-name">
            <span>{{ $item->name }}</span>
        </div>
    </a>
</li>
