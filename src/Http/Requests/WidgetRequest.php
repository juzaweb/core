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

class WidgetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'content' => 'array',
            'content.*.key' => 'required|string|max:255',
            'content.*.widget' => 'required|string|max:255',
        ];
    }
}
