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

class NotificationSubscribeRequest extends FormRequest
{
    public function rules(): array
    {
        $channel = $this->route('channel');
        $name = $channel === 'mail' ? 'email' : 'token';

        $rules = [
			"{$name}" => ['required'],
		];

        if ($channel === 'mail') {
            $rules["{$name}"][] = 'email:rfc';
        }

        return $rules;
    }
}
