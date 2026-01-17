<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Translations;

use IP2Location\Database;
use RuntimeException;

class IP2LocationFactory implements \Juzaweb\Modules\Core\Translations\Contracts\IP2Location
{
    public function __construct(protected string $dataPath)
    {
        if (!file_exists($this->dataPath)) {
            throw new RuntimeException("IP2Location database file not found at {$this->dataPath}");
        }
    }

    /**
     * Get the country code for the given IP address.
     *
     * @param string $ip The IP address to look up.
     * @return bool|string The country code or false if not found.
     */
    public function countryCode(string $ip): bool|string
    {
        return $this->lookup($ip, Database::COUNTRY_CODE);
    }


    /**
     * Lookup the IP address and return the location data.
     *
     * @param string $ip The IP address to look up.
     * @param array|int|null $fields The fields to return, or null for all fields.
     * @return array|bool|string The location data for the IP address.
     */
    public function lookup(string $ip, array|int $fields = null): bool|array|string
    {
        return (new Database($this->dataPath, Database::FILE_IO))->lookup($ip, $fields);
    }
}
