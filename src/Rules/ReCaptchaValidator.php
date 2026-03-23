<?php

namespace Juzaweb\Modules\Core\Rules;

use Illuminate\Support\Facades\Http;

class ReCaptchaValidator
{
    public function validate($attribute, $value, $parameters, $validator): bool
    {
        $secretKey = setting('captcha_site_secret') ?: (config('services.recaptcha.secret') ?: config('network.recaptcha.secret_key'));

        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret' => $secretKey,
                'response' => $value,
            ]
        );

        return $response->json('success');
    }
}
