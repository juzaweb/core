<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Translations\Contracts;

interface IP2Location
{
    public function countryCode(string $ip): bool|string;

    public function lookup(string $ip, array|int $fields = null);
}
