<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    larabizcom/larabiz
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Juzaweb\Modules\Core\Enums\CommentStatus;
use Juzaweb\Modules\Core\Rules\AllExist;

class CommentActionsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'action' => ['required', Rule::in(array_keys(CommentStatus::all()))],
            'ids' => ['required', 'array', 'min:1', new AllExist('comments', 'id')],
        ];
    }
}
