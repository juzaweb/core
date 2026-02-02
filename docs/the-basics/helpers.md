The following functions provide utility functionality that can be used throughout the application.

## Breadcrumb

The `Breadcrumb` facade (mapped to `Juzaweb\Modules\Core\Contracts\Breadcrumb`) allows you to manage breadcrumbs in your application.

### Usage

```php
use Juzaweb\Modules\Core\Facades\Breadcrumb;

// Add a breadcrumb item
Breadcrumb::add('Home', '/');
Breadcrumb::add('Blog', '/blog');
Breadcrumb::add('My Post'); // No URL for the last item

// Add multiple items
Breadcrumb::items([
    [
        'title' => 'Home',
        'url' => '/',
    ],
    [
        'title' => 'Products',
        'url' => '/products',
    ],
]);
```

### Methods

- `add(string $title, string $url = null)`: Add a single item.
- `items(array $items)`: Set all items (replaces existing).
- `addItems(array $items)`: Append multiple items.
- `getItems()`: Get the array of items.
- `toArray()`: Get items as array.

## Thumbnails

The `Thumbnail` facade allows you to define global default thumbnail images for your models. This is useful when you want to show a placeholder image if a model doesn't have an uploaded thumbnail.

### Usage

#### 1. Add Trait to Model

First, ensure your model uses the `Juzaweb\Modules\Core\Traits\HasThumbnail` trait. This trait provides the `thumbnail` attribute and handling logic.

```php
namespace Juzaweb\Modules\Blog\Models;

use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Traits\HasThumbnail;

class Post extends Model
{
    use HasThumbnail;

    // ...
}
```

#### 2. Register Default Thumbnails

You can register the default thumbnail URLs in the `boot` method of your `ServiceProvider` using the `Thumbnail` facade.

```php
use Juzaweb\Modules\Core\Facades\Thumbnail;
use Juzaweb\Modules\Blog\Models\Post;

public function boot()
{
    Thumbnail::defaults(function () {
        return [
            Post::class => asset('assets/images/post-placeholder.png'),
        ];
    });
}
```

#### 3. Retrieving Thumbnail

Now, when you access the `thumbnail` attribute of your model, it will return the uploaded image (via HasMedia) or the default placeholder if none exists.

```php
$post = Post::find(1);

// Returns uploaded thumbnail or 'assets/images/post-placeholder.png'
echo $post->thumbnail;
```

### How It Works

1.  `Thumbnail::defaults` registers a callback returning the configuration array.
2.  A global Middleware runs on every request, retrieves these defaults, and injects them into the respective Models using the `defaultThumbnail()` static method provided by the `HasThumbnail` trait.
3.  The `getThumbnailAttribute` accessor checks `getFirstMediaUrl('thumbnail')`. If it's null, it falls back to the injected static default URL.

## Sitemap

Juzaweb CMS uses the `Sitemap` facade (mapped to `Juzaweb\Modules\Core\Contracts\Sitemap`) to manage XML sitemaps. It integrates with Spatie's Sitemap package but adds a registry system for modules.

### Usage

```php
use Juzaweb\Modules\Core\Facades\Sitemap;
```

### Methods

#### register($key, $class)

Register a new sitemap provider. The class must implement `Juzaweb\Modules\Core\Contracts\Sitemapable`.

```php
use Juzaweb\Modules\Core\Facades\Sitemap;

public function boot()
{
    Sitemap::register('posts', \Juzaweb\Modules\Blog\Models\Post::class);
}
```

#### all()

Get all registered sitemap providers.

```php
$providers = Sitemap::all();
```

#### get($key)

Get a specific sitemap provider class by key.

```php
$class = Sitemap::get('posts');
```

### Implementing Sitemapable

To make a Model or Class compatible with the Sitemap system, implement the `Juzaweb\Modules\Core\Contracts\Sitemapable` interface.

```php
namespace Juzaweb\Modules\Blog\Models;

use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Contracts\Sitemapable;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Sitemap\Tags\Url;

class Post extends Model implements Sitemapable
{
    /**
     * Query scope for sitemap generation.
     */
    public function scopeForSitemap(Builder $builder): Builder
    {
        return $builder->where('status', 'publish');
    }

    /**
     * Get the URL for the sitemap item.
     */
    public function getUrl(): string
    {
        return route('post.detail', [$this->slug]);
    }

    /**
     * Get the sitemap page name (group).
     */
    public static function getSitemapPage(): string
    {
        return 'posts';
    }

    /**
     * Map the model to a Sitemap Tag (Spatie).
     */
    public function toSitemapTag(): Url|string|array
    {
        return Url::create($this->getUrl())
            ->setLastModificationDate($this->updated_at)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.8);
    }
}
```

## System & Configuration

### `client_ip()`
Get client IP address. Supports Cloudflare `HTTP_CF_CONNECTING_IP`.

### `setting($key = null, $default = null)`
Get or set a setting value.
```php
$value = setting('site_title', 'My Site');
```

### `is_super_admin($user = null)`
Check if a user is a super admin.

### `is_admin_page()`
Determine if the current page is an admin page.

### `default_language()`
Get the default language locale (e.g., 'en').

### `languages()`
Get a collection of all available languages.

### `current_actor($guard = null)`
Get the current authenticated user or a Guest model instance.

### `user($guard = null)`
Get the currently authenticated user.

### `cache_prefix($key)`
Get the prefix for cache keys using the application scope.

## URL & Paths

### `home_url($uri = null, $locale = null)`
Generate a home URL. Handles multi-language prefixes automatically.
```php
echo home_url('contact');
```

### `admin_url($uri = null)`
Generate an admin panel URL.
```php
echo admin_url('posts/create');
```

### `upload_url($path)`
Get the full URL for an uploaded file. Handles local and cloud storage.

### `theme_path($path = null)`
Get the absolute path to the theme directory.

### `theme_asset($path, $theme = null)`
Get the URL for a theme asset.
```php
echo theme_asset('css/style.css');
```

### `upload_path_format($path)`
Format a URL back to a relative storage path.

### `back_form_url()`
Generate a URL to redirect back to, usually the index page of a resource, stripping `create` or `edit` segments.

### `load_data_url($model, $field = 'name', $params = [])`
Generate a URL for AJAX loading of data (Select2, etc.).

## String & Formatting

### `is_json($string)`
Check if a string is valid JSON.

### `seo_string($string, $chars = 70)`
Generate an SEO-friendly string (strips tags, decodes entities, truncates).

### `str_slug($string)`
Generate a URL-friendly slug.

### `title_from_key($key)`
Generate a readable title from a key (e.g., `user_manager` -> `User Manager`).

### `key_from_string($str)`
Convert a string to a key format (slug with underscores).

### `number_human_format($number)`
Format a number to human-readable format (1K, 1M, 1B).

### `map_params($text, $params)`
Replace `{placeholders}` in text with values from an array.

### `array_to_array_string($array)`
Convert an array to a string representation (useful for logging/debugging).

### `remove_zero_width_space_string($string)`
Remove zero-width characters from a string.

## Media & External

### `proxy_image($url, $width = null, $height = null, $crop = false)`
Generate a proxy URL for an image, allowing resizing and caching.

### `get_youtube_id($url)`
Extract YouTube video ID from a URL.

### `get_vimeo_id($url)`
Extract Vimeo video ID from a URL.

### `get_google_drive_id($url)`
Extract Google Drive file ID from a URL.

## Date & Time

### `date_range($from, $to, $format = 'd/m/Y')`
Generate an array of dates between two dates.

### `month_range($from, $to, $format = 'm/Y')`
Generate an array of months between two dates.

## Security & Encryption

### `encrypt_deterministic($plaintext, $key)`
Encrypt a string deterministically (same input always produces same output for a given key).

### `decrypt_deterministic($token, $key)`
Decrypt a deterministically encrypted string.

### `base64url_encode($data)` / `base64url_decode($data)`
Base64 encode/decode with URL-safe characters.

## Error Handling

### `get_error_by_exception(Throwable $e)`
Extract standardized error details (message, line, file, code) from an exception.
