<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Juzaweb\Modules\Core\Rules\XssBlock;

class TranslationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'group' => 'required|string|max:255',
            'namespace' => 'required|string|max:255',
            'key' => 'required|string|max:255',
            'value' => ['required', 'string', new XssBlock()],
        ];
    }
}
