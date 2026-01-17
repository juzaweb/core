<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class XssBlock implements ValidationRule
{
    protected array $patterns = [
        '/<script/i',
        '/javascript:/i',
        '/eval\(/i',
        '/onload/i',
        '/onclick/i',
        '/onerror/i',
        '/onmouseover/i',
        '/onfocus/i',
        '/onblur/i',
        '/onchange/i',
        '/onsubmit/i',
        '/<iframe>/i',
        '/<object/i',
        '/<embed/i',
        '/<applet/i',
        '/<form/i',
        '/base64/i',
        '/data:text\/html/i',
    ];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        foreach ($this->patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                $fail(__('core::translation.invalid_value'));
                return;
            }
        }
    }
}
