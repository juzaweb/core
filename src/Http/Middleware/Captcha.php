<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Http\Middleware;

use Closure;
use GuzzleHttp\Client;
use Juzaweb\Modules\Core\Traits\HasRestResponses;

class Captcha
{
    use HasRestResponses;

    public function handle($request, Closure $next)
    {
        if (config('network.recaptcha.site_key')) {
            $client = new Client(['connect_timeout' => 10, 'timeout' => 10]);
            $response = $client->post(
                'https://www.google.com/recaptcha/api/siteverify',
                [
                    'form_params' => [
                        'secret' => config('network.recaptcha.secret_key'),
                        'response' => $request->input('g-recaptcha-response'),
                    ],
                ]
            );

            $body = json_decode((string)$response->getBody(), false, 512, JSON_THROW_ON_ERROR);

            abort_if(!$body->success, 400, __('admin::translation.captcha_validation_failed'));
        }

        return $next($request);
    }
}
