<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @author     The Anh Dang
 *
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
        $captcha = setting('captcha');
        $siteKey = setting('captcha_site_key') ?: config('network.recaptcha.site_key');
        $secretKey = setting('captcha_site_secret') ?: config('network.recaptcha.secret_key');

        if (is_null($captcha) && $siteKey) {
            $captcha = 'recaptcha-v2-invisible';
        }

        if ($captcha == 'recaptcha-v2-invisible' && $siteKey) {
            $client = new Client(['connect_timeout' => 10, 'timeout' => 10]);
            $response = $client->post(
                'https://www.google.com/recaptcha/api/siteverify',
                [
                    'form_params' => [
                        'secret' => $secretKey,
                        'response' => $request->input('g-recaptcha-response'),
                    ],
                ]
            );

            $body = json_decode((string) $response->getBody(), false, 512, JSON_THROW_ON_ERROR);

            abort_if(! $body->success, 400, __('core::translation.captcha_validation_failed'));
        }

        return $next($request);
    }
}
