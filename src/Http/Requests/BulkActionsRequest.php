<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkActionsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'action' => 'required|string',
            'ids' => 'required|array',
            'ids.*' => 'required',
        ];
    }
}
