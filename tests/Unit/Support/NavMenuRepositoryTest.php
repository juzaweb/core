<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Support;

use Juzaweb\Modules\Core\Support\NavMenuRepository;
use Juzaweb\Modules\Core\Tests\TestCase;

class NavMenuRepositoryTest extends TestCase
{
    public function test_get_returns_null_when_key_does_not_exist()
    {
        $repository = new NavMenuRepository();

        $this->assertNull($repository->get('non_existent_key'));
    }

    public function test_get_returns_evaluated_template_when_key_exists()
    {
        $repository = new NavMenuRepository();

        $repository->make('existent_key', function () {
            return [
                'name' => 'Home',
                'url' => '/',
            ];
        });

        $expected = [
            'name' => 'Home',
            'url' => '/',
        ];

        $this->assertEquals($expected, $repository->get('existent_key'));
    }
}
