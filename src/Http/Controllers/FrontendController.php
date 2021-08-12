<?php
/**
 * MYMO CMS - Free Laravel CMS
 *
 * @package    juzawebcms/juzawebcms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://github.com/juzawebcms/juzawebcms
 * @license    MIT
 *
 * Created by The Anh.
 * Date: 5/24/2021
 * Time: 8:36 PM
 */

namespace Juzaweb\Core\Http\Controllers;

use Juzaweb\Core\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use juzaweb\Theme\Facades\Theme;

class FrontendController extends Controller
{
    /**
     * Set a layout properties here, so you can globally call it in all of your Controllers
     */
    protected $layout = 'frontend::layout';

    public function callAction($method, $parameters)
    {
        /**
         * TAD CMS: Action after call action frontend
         * Add action to this hook add_action('frontend.call_action', $callback)
         * */
        do_action('frontend.call_action', $method, $parameters);

        Theme::set($this->getCurrentTheme());

        return parent::callAction($method, $parameters);
    }

    protected function getCurrentTheme()
    {
        return get_config('activated_theme', 'juzaweb');
    }

    protected function setupLayout()
    {
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }

    protected function view(string $view)
    {
        $this->setupLayout();
        $this->layout->content = View::make($view);
        return $this->layout;
    }
}