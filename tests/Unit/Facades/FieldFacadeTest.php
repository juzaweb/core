<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Facades;

use Juzaweb\Modules\Core\Contracts\Field as FieldContract;
use Juzaweb\Modules\Core\Facades\Field;
use Juzaweb\Modules\Core\Support\FieldFactory;
use Juzaweb\Modules\Core\Support\Fields\Text;
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
        // Test calling a method on the facade returns the expected field object
        $field = Field::text('Label', 'name', ['option' => 'value']);

        $this->assertInstanceOf(Text::class, $field);

        // Since FieldFactory methods return new instances, we can check properties if they are accessible
        // or just verify the instance type is correct as above.
        // Let's also check if we can mock it

        Field::shouldReceive('textarea')
            ->once()
            ->with('Label', 'description', [])
            ->andReturn(new \Juzaweb\Modules\Core\Support\Fields\Textarea('Label', 'description', []));

        $result = Field::textarea('Label', 'description', []);

        $this->assertInstanceOf(\Juzaweb\Modules\Core\Support\Fields\Textarea::class, $result);
    }
}
