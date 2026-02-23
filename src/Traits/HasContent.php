<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Traits;

use Webwizo\Shortcodes\Facades\Shortcode;

trait HasContent
{
    public function renderContent()
    {
        if ($this->content === null) {
            return '';
        }

        $content = Shortcode::compile($this->content);
        $html = str_get_html($content);

        if ($html === false) {
            return $content;
        }

        foreach ($html->find('img') as $item) {
            if (!empty($item->src) && !str_starts_with($item->src, 'http')) {
                $item->src = upload_url($item->src);
            }
        }

        return remove_zero_width_space_string($html->save());
    }
}
