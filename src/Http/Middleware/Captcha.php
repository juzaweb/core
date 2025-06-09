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
        if (config('services.recaptcha.key')) {
            $client = new Client();
            $response = $client->post(
                'https://www.google.com/recaptcha/api/siteverify',
                [
                    'form_params' => [
                        'secret' => config('services.recaptcha.secret'),
                        'response' => $request->input('captcha'),
                    ],
                ]
            );

            $body = json_decode((string)$response->getBody(), false, 512, JSON_THROW_ON_ERROR);

            if (!$body->success) {
                return $this->restFail(__('Captcha validation failed'));
            }
        }

        return $next($request);
    }
}
