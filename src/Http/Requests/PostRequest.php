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
use Juzaweb\Modules\Core\Rules\AllExist;

class PostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
			'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string', 'max:50000'],
            'slug' => ['nullable', 'string', 'max:255'],
            "thumbnail" => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'in:draft,published,private'],
            'categories' => ['nullable', 'array', AllExist::make('post_categories','id')],
            'tags' => ['nullable', 'array'],
		];
    }
}
