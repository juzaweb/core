@php
    /** @var \Juzaweb\Modules\Core\Support\Entities\Widget $widget */
    /** @var \Juzaweb\Modules\Core\Models\ThemeSidebar $sidebar */
    $content = $sidebar->data['content'] ?? '';
@endphp

@if($content)
    {!! $content !!}
@endif
