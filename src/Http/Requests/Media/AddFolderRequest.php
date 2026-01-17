<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     Juzaweb Team <admin@juzaweb.com>
 * @link       https://juzaweb.com
 * @license    MIT
 */

namespace Juzaweb\Modules\Core\Http\Requests\Media;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Juzaweb\Modules\Core\FileManager\Enums\MediaType;
use Juzaweb\Modules\Core\Models\Media;

class AddFolderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::modelUnique(
                    Media::class,
                    'name',
                    function ($q) {
                        $q->where('type', MediaType::DIRECTORY);
                        $q->where('name', '=', $this->input('name'));
                        $q->where('parent_id', '=', $this->input('folder_id'));
                    }
                )
            ],
            'folder_id' => 'nullable|exists:media,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => trans('cms::filemanager.folder-name'),
            'folder_id' => trans('cms::filemanager.parent'),
        ];
    }
}
