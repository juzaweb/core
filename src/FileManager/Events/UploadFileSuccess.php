<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Modules\Core\FileManager\Events;

use Juzaweb\Modules\Core\Models\Media;

class UploadFileSuccess
{
    public function __construct(public Media $media, public bool $overwrite = false)
    {
    }
}
