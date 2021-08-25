<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzawebcms/juzawebcms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://github.com/juzawebcms/juzawebcms
 * @license    MIT
 *
 * Created by JUZAWEB.
 * Date: 5/25/2021
 * Time: 10:10 PM
 */

namespace Juzaweb\Core\Http\Controllers;

use Juzaweb\Core\Facades\PostType;
use Juzaweb\Core\Traits\ResponseMessage;

class BackendController extends Controller
{
    use ResponseMessage;

    public function callAction($method, $parameters)
    {
        do_action('backend.call_action', $method, $parameters);

        if (!file_exists(storage_path('app/installed'))) {
            if (!in_array(\Route::currentRouteName(), ['install', 'install.submit', 'install.submit.step'])) {
                return redirect()->route('install');
            }
        }

        if (config('juzaweb::app.demo', false) == 'true' && \Auth::id() != 1) {
            if (\request()->isMethod('post')) {
                if (\request()->is('admin-cp/*')) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'You cannot change the demo version',
                    ]);
                }

                if (\request()->is('account/change-password')) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'You cannot change the demo version',
                    ]);
                }
            }
        }

        $types = PostType::getPostTypes();
        foreach ($types as $key => $type) {
            add_action('post_type.'.$key.'.form.rigth', function ($model) use ($key) {
                echo view('juzaweb::components.taxonomies', [
                    'postType' => $key,
                    'model' => $model
                ])->render();
            });
        }

        return parent::callAction($method, $parameters);
    }

    protected function addBreadcrumb(array $item, $name = 'admin')
    {
        add_filters($name . '_breadcrumb', function ($items) use ($item) {
            $items[] = $item;
            return $items;
        });
    }
}