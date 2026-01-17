<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Translations\Commands;

use Illuminate\Console\Command;

class ConvertConfigCountryCommand extends Command
{
    protected $name = 'translation:convert-config-country';

    protected $description = 'Convert config country to translations';

    public function handle()
    {
        // Source https://www.ip2location.com/free/country-information
        $csv = fopen(__DIR__ . '/../../database/csv/IP2LOCATION-COUNTRY-INFORMATION.CSV', 'rb');
        $config = [];
        $header = fgetcsv($csv);
        while (($row = fgetcsv($csv)) !== false) {
            [$code, $name] = $row;
            $lowerCode = strtolower($code);
            $config[$lowerCode] = [
                'name' => $name,
                'code' => $lowerCode,
                'language' => strtolower($row[13]),
            ];
        }

        fclose($csv);

        file_put_contents(
            __DIR__ . '/../config/countries.php',
            '<?php' . PHP_EOL.PHP_EOL . 'return ' . custom_var_export($config) . ';' . PHP_EOL
        );
    }
}
