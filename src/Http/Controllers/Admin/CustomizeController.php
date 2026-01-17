<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Http\Controllers\Admin;

use Illuminate\Support\Collection;
use Juzaweb\Modules\Core\Facades\Breadcrumb;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;
use Juzaweb\Modules\Core\Support\Customizes\Customize;
use Juzaweb\Modules\Core\Support\Customizes\CustomizeControl;

class CustomizeController extends AdminController
{
    public function index()
    {
        Breadcrumb::add(__('admin::translation.customize'));

        $customize = new Customize();

        $customize->addSection(
            'site_identity',
            [
                'title' => __('admin::translation.site_identity'),
                'priority' => 1,
            ]
        );

        $customize->addControl(
            new CustomizeControl(
                $customize,
                'site_identity',
                [
                    'label' => __('admin::translation.site_identity'),
                    'section' => 'site_identity',
                    'settings' => 'site_identity',
                    'type' => 'site_identity',
                ]
            )
        );

        /**
         * @var Customize $customize
         */
        $customize = apply_filters('theme_editor.get_customize', $customize);
        $panels = $customize->getPanel()->sortBy('priority');

        foreach ($panels as $key => $panel) {
            $sections = $customize->getSection()->where('panel', $key);
            if ($sections->isEmpty()) {
                continue;
            }

            $childs = $panel->get('childs', new Collection([]));
            foreach ($sections as $secKey => $section) {
                $controls = $customize->getControl()->where('section', $secKey);
                $section->put('controls', $controls);

                $childs->put($secKey, $section);
            }

            $panel->put('childs', $childs);
        }

        $sections = $customize->getSection()->whereNull('panel');
        foreach ($sections as $secKey => $section) {
            $controls = $customize->getControl()->where('section', $secKey);
            $section->put('controls', $controls);
            $panels->put($secKey, $section);
        }

        return view(
            'admin::admin.customize.index',
            compact('panels')
        );
    }

    public function update()
    {

    }
}
