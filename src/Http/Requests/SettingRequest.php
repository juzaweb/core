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
use Juzaweb\Core\Contracts\Setting;

class SettingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $settings = app(Setting::class)->settings();

        return $this->collect()
            ->only($settings->keys())
            ->mapWithKeys(
                fn ($value, $key) => [
                    $key => $settings[$key]['rules'] ?: ['nullable', 'string']
                ]
            )
            ->filter()
            ->toArray();
    }
}
