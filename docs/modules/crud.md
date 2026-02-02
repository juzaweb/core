## Make CRUD

Create migration file for your feature:

```php
Schema::create('posts', function(Blueprint $table) {
    $table->increments('id');
    $table->string('author');
    $table->timestamps();
});

Schema::create('post_translations', function(Blueprint $table) {
    $table->increments('id');
    $table->integer('post_id')->unsigned();
    $table->string('locale')->index();
    $table->string('title');
    $table->text('content');

    $table->unique(['post_id', 'locale']);
    $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
});
```

Make model for your feature:

```bash
php artisan module:make-model Post ModuleName
```

Make CRUD in Admin panel and API:

```bash
php artisan module:make-crud Post ModuleName
```
