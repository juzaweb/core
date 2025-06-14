<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    larabizcom/larabiz
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\Core\Rules;

class DomainValidator
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        return filter_var($value, FILTER_VALIDATE_DOMAIN)
            && (bool) preg_match(
                '/^(?:[a-z0-9](?:[a-z0-9-æøå]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]$/isu',
                $value
            );
    }
}
