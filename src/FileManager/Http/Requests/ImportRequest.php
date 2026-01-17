<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Modules\Core\FileManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'working_dir' => ['nullable', 'uuid'],
            'url' => ['required', 'string', 'url'],
            'download' => ['nullable', 'boolean']
        ];
    }
}
