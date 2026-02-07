# Thumbnails

The `Thumbnail` facade allows you to define global default thumbnail images for your models. This is useful when you want to show a placeholder image if a model doesn't have an uploaded thumbnail.

## Usage

### 1. Add Trait to Model

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

The `HasThumbnail` trait automatically:
1.  Appends the `thumbnail` attribute to the model (so it appears in `$model->toArray()` or JSON responses).
2.  Provides the `setThumbnail($media)` helper method to easily attach/update the thumbnail.

```php
// Update thumbnail
$post->setThumbnail($request->input('thumbnail'));
```

### 2. Register Default Thumbnails

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

### 3. Retrieving Thumbnail

Now, when you access the `thumbnail` attribute of your model, it will return the uploaded image (via HasMedia) or the default placeholder if none exists.

```php
$post = Post::find(1);

// Returns uploaded thumbnail or 'assets/images/post-placeholder.png'
echo $post->thumbnail;
```

## How It Works

1.  `Thumbnail::defaults` registers a callback returning the configuration array.
2.  A global Middleware runs on every request, retrieves these defaults, and injects them into the respective Models using the `defaultThumbnail()` static method provided by the `HasThumbnail` trait.
3.  The `getThumbnailAttribute` accessor checks `getFirstMediaUrl('thumbnail')`. If it's null, it falls back to the injected static default URL.
