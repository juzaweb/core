<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Facades;

use Juzaweb\Modules\Core\Contracts\PageTemplate as PageTemplateContract;
use Juzaweb\Modules\Core\Facades\PageTemplate;
use Juzaweb\Modules\Core\Support\Entities\PageTemplate as PageTemplateEntity;
use Juzaweb\Modules\Core\Support\PageTemplateRepository;
use Juzaweb\Modules\Core\Tests\TestCase;

class PageTemplateFacadeTest extends TestCase
{
    public function test_facade_resolves_to_implementation()
    {
        $this->assertInstanceOf(PageTemplateRepository::class, PageTemplate::getFacadeRoot());
        $this->assertInstanceOf(PageTemplateContract::class, PageTemplate::getFacadeRoot());
    }

    public function test_register_and_get_template()
    {
        $key = 'test_template';
        $options = [
            'label' => 'Test Template',
            'blocks' => ['block1', 'block2']
        ];

        PageTemplate::make($key, function () use ($options) {
            return $options;
        });

        $template = PageTemplate::get($key);

        $this->assertInstanceOf(PageTemplateEntity::class, $template);
        $this->assertEquals($key, $template->key);
        $this->assertEquals($options['label'], $template->label);
        $this->assertEquals($options['blocks'], $template->blocks);
    }

    public function test_get_returns_null_for_unknown_template()
    {
        $this->assertNull(PageTemplate::get('unknown_template'));
    }

    public function test_all_returns_collection_of_templates()
    {
        $templatesData = [
            'tpl1' => ['label' => 'Template 1'],
            'tpl2' => ['label' => 'Template 2'],
        ];

        foreach ($templatesData as $key => $data) {
            PageTemplate::make($key, fn() => $data);
        }

        $allTemplates = PageTemplate::all();

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $allTemplates);
        // Note: other templates might be registered by the system/tests, so we check if ours are present
        $this->assertTrue($allTemplates->has('tpl1'));
        $this->assertTrue($allTemplates->has('tpl2'));

        $this->assertEquals('Template 1', $allTemplates->get('tpl1')->label);
        $this->assertEquals('Template 2', $allTemplates->get('tpl2')->label);
    }

    public function test_facade_mocking()
    {
        PageTemplate::shouldReceive('get')
            ->once()
            ->with('mock_template')
            ->andReturn(new PageTemplateEntity('mock_template', ['label' => 'Mocked']));

        $template = PageTemplate::get('mock_template');

        $this->assertEquals('Mocked', $template->label);
    }
}
