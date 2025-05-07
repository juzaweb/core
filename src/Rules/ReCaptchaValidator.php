<?php

namespace Juzaweb\Core\Rules;

use GuzzleHttp\Client;

class ReCaptchaValidator
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        $client = new Client();
        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'form_params' => [
                    'secret' => config('services.recaptcha.secret'),
                    'response' => $value,
                ],
            ]
        );

        $body = json_decode((string) $response->getBody(), false, 512, JSON_THROW_ON_ERROR);

        return $body->success;
    }
}
