<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Contracts;

interface Viewable
{
    public function dailyViews();

    public function incrementViews($viewer, ?string $ip, int $count = 1): void;
}
