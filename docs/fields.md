# Form Fields

Juzaweb CMS provides a fluent API for generating form fields using the `Field` facade. These fields are typically used in Meta Box registrations or custom form implementations.

## Usage

```php
use Juzaweb\Modules\Core\Facades\Field;

Field::text($label, $name, $options);
```

### Common Options
Most fields accept an `$options` array with the following common keys:
- `default`: Default value.
- `class`: Additional CSS classes.
- `id`: Custom ID attribute.
- `description`: Help text below the field.
- `disabled`: Disable the field.

## Available Field Types

### Text
Standard text input.
```php
Field::text('Full Name', 'name', ['default' => 'John Doe']);
```

### Textarea
Multiline text input.
```php
Field::textarea('Bio', 'bio', ['rows' => 5]);
```

### Editor
WYSIWYG Editor (TinyMCE/CKEditor).
```php
Field::editor('Content', 'content');
```

### Select
Dropdown list.
```php
Field::select('Status', 'status', [
    'options' => [
        'draft' => 'Draft',
        'published' => 'Published'
    ]
]);
```

### Image
Single image uploader.
```php
Field::image('Thumbnail', 'thumbnail');
```

### Images
Multiple image uploader (Gallery).
```php
Field::images('Gallery', 'gallery');
```

### UploadUrl
File upload or URL input.
```php
Field::uploadUrl('Video', 'video_url');
```

### Checkbox
Single checkbox.
```php
Field::checkbox('Is Featured', 'is_featured');
```

### Date
Date picker.
```php
Field::date('Publish Date', 'publish_date');
```

### Tags
Tag input.
```php
Field::tags('Tags', 'tags');
```

### Slug
Slug generation field, usually paired with a source field.
```php
Field::slug('Slug', 'slug', ['target' => 'name']);
```

### Security
Security question or captcha field (specific implementation may vary).
```php
Field::security('Verification', 'nonce');
```

### Currency
Product price or currency input.
```php
Field::currency('Price', 'price');
```

### Language
Language selector.
```php
Field::language('Language', 'language_code');
```
