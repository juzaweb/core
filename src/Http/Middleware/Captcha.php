<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Http\Middleware;

use Closure;
use GuzzleHttp\Client;
use Juzaweb\Core\Helpers\Traits\RestResponses;

class Captcha
{
    use RestResponses;

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
