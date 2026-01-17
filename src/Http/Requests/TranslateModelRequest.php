<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TranslateModelRequest extends FormRequest
{
    public function rules(): array
    {
        return [
			'model' => [
                'required',
                'string',
            ],
            'ids' => [
                'required',
            ],
            'locale' => [
                'required',
                'string',
                Rule::in(array_keys(config('locales', []))),
            ],
		];
    }
}
