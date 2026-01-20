<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Facades;

use Juzaweb\Modules\Core\Contracts\Field as FieldContract;
use Juzaweb\Modules\Core\Facades\Field;
use Juzaweb\Modules\Core\Support\FieldFactory;
use Juzaweb\Modules\Core\Support\Fields\Checkbox;
use Juzaweb\Modules\Core\Support\Fields\Currency;
use Juzaweb\Modules\Core\Support\Fields\Date;
use Juzaweb\Modules\Core\Support\Fields\Editor;
use Juzaweb\Modules\Core\Support\Fields\Image;
use Juzaweb\Modules\Core\Support\Fields\Images;
use Juzaweb\Modules\Core\Support\Fields\Language;
use Juzaweb\Modules\Core\Support\Fields\Security;
use Juzaweb\Modules\Core\Support\Fields\Select;
use Juzaweb\Modules\Core\Support\Fields\Slug;
use Juzaweb\Modules\Core\Support\Fields\Tags;
use Juzaweb\Modules\Core\Support\Fields\Text;
use Juzaweb\Modules\Core\Support\Fields\Textarea;
use Juzaweb\Modules\Core\Support\Fields\UploadUrl;
use Juzaweb\Modules\Core\Tests\TestCase;

class FieldFacadeTest extends TestCase
{
    public function test_facade_resolves_to_implementation()
    {
        $this->assertInstanceOf(FieldFactory::class, Field::getFacadeRoot());
        $this->assertInstanceOf(FieldContract::class, Field::getFacadeRoot());
    }

    public function test_facade_method_calls()
    {
        // Text
        $field = Field::text('Label', 'name', ['option' => 'value']);
        $this->assertInstanceOf(Text::class, $field);

        // Textarea
        $field = Field::textarea('Label', 'name');
        $this->assertInstanceOf(Textarea::class, $field);

        // Checkbox
        $field = Field::checkbox('Label', 'name');
        $this->assertInstanceOf(Checkbox::class, $field);

        // Select
        $field = Field::select('Label', 'name');
        $this->assertInstanceOf(Select::class, $field);

        // Editor
        $field = Field::editor('Label', 'name');
        $this->assertInstanceOf(Editor::class, $field);

        // Language
        $field = Field::language('Label', 'name');
        $this->assertInstanceOf(Language::class, $field);

        // UploadUrl
        $field = Field::uploadUrl('Label', 'name');
        $this->assertInstanceOf(UploadUrl::class, $field);

        // Tags
        $field = Field::tags('Label', 'name');
        $this->assertInstanceOf(Tags::class, $field);

        // Image
        $field = Field::image('Label', 'name');
        $this->assertInstanceOf(Image::class, $field);

        // Images
        $field = Field::images('Label', 'name');
        $this->assertInstanceOf(Images::class, $field);

        // Date
        $field = Field::date('Label', 'name');
        $this->assertInstanceOf(Date::class, $field);

        // Slug
        $field = Field::slug('Label', 'name');
        $this->assertInstanceOf(Slug::class, $field);

        // Security
        $field = Field::security('Label', 'name');
        $this->assertInstanceOf(Security::class, $field);

        // Currency
        $field = Field::currency('Label', 'name');
        $this->assertInstanceOf(Currency::class, $field);
    }
}
