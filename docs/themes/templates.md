# Templates & Blocks

Juzaweb CMS allows themes to define custom page templates and reusable page blocks.

## Page Templates (`PageTemplate`)

The `Juzaweb\Modules\Core\Contracts\PageTemplate` contract is used to register custom page templates that users can select when editing a page.

### Usage

```php
use Juzaweb\Modules\Core\Facades\PageTemplate;

// Register a custom template
PageTemplate::make('landing', function () {
    return [
        'label' => 'Landing Page',
        'view' => 'theme::templates.landing',
    ];
});
```

### Methods

- `make(string $key, callable $callback)`: Register a new page template. Callback must return an array.
- `get(string $key)`: Get a specific page template.
- `all()`: Get all registered page templates.

## Page Blocks (`PageBlock`)

The `Juzaweb\Modules\Core\Contracts\PageBlock` contract is used to register custom blocks for the Page Builder.

### Usage

```php
use Juzaweb\Modules\Core\Facades\PageBlock;

// Register a new block
PageBlock::make('hero_banner', function () {
    return [
        'label' => 'Hero Banner',
        'view' => 'theme::blocks.hero_banner',
        'options' => [
            'title' => 'text',
            'background' => 'image',
        ],
    ];
});
```

### Methods

- `make(string $key, callable $callback)`: Register a new page block. Callback must return an array.
- `get(string $key)`: Get a specific page block.
- `all()`: Get all registered page blocks.
