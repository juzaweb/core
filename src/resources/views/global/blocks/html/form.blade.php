{{ Field::text(__('core::translation.label'), "{$name}[label]", ['value' => $data['label'] ?? '']) }}

{{ Field::textarea(__('core::translation.html_content'), "{$name}[content]", ['value' => $data['content'] ?? '', 'rows' => 5]) }}
