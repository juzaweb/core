@php
    $taxonomies = \Juzaweb\Core\Support\PostType::getTaxonomies($postType);
@endphp

@foreach($taxonomies as $taxonomy)
    @component('juzaweb::components.form_taxonomies', [
        'taxonomy' => $taxonomy,
        'model' => $model
    ])@endcomponent
@endforeach