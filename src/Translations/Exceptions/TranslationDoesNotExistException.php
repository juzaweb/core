<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Translations\Exceptions;

class TranslationDoesNotExistException extends TranslationException
{
    public static function make(string $locale): static
    {
        return new static("Source {$locale} translation does not exist.");
    }
}
