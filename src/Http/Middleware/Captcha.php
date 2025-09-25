<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Http\Middleware;

use Closure;
use GuzzleHttp\Client;
use Juzaweb\Core\Traits\HasRestResponses;

class Captcha
{
    use HasRestResponses;

    public function handle($request, Closure $next)
    {
        if (setting('recaptcha2_site_key')) {
            $client = new Client(['connect_timeout' => 10, 'timeout' => 10]);
            $response = $client->post(
                'https://www.google.com/recaptcha/api/siteverify',
                [
                    'form_params' => [
                        'secret' => setting('recaptcha2_secret_key'),
                        'response' => $request->input('g-recaptcha-response'),
                    ],
                ]
            );

            $body = json_decode((string)$response->getBody(), false, 512, JSON_THROW_ON_ERROR);

            abort_if(!$body->success, 400, __('Captcha validation failed'));
        }

        return $next($request);
    }
}
