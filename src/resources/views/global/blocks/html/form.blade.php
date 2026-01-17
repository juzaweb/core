{{ Field::text(__('admin::translation.label'), "{$name}[label]", ['value' => $data['label'] ?? '']) }}

{{ Field::textarea(__('admin::translation.html_content'), "{$name}[content]", ['value' => $data['content'] ?? '', 'rows' => 5]) }}
