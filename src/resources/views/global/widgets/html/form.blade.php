{{ Field::textarea(__('core::translation.html_content'), "content[{$data['key']}][content]", ['value' => data_get($data, 'content', ''), 'rows' => 5]) }}
