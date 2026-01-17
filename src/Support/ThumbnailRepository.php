<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Support;

use Juzaweb\Modules\Core\Contracts\Thumbnail;

class ThumbnailRepository implements Thumbnail
{
    protected \Closure $defaults;

    public function defaults(\Closure $callback): void
    {
        $this->defaults = $callback;
    }

    public function getDefaults(): array
    {
        if (isset($this->defaults)) {
            $callback = $this->defaults;
            return $callback();
        }

        return [];
    }
}
