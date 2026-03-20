<?php

namespace Juzaweb\Modules\Core\Rules;

use Illuminate\Support\Facades\Http;

class ReCaptchaValidator
{
    public function validate($attribute, $value, $parameters, $validator): bool
    {
        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret' => config('services.recaptcha.secret'),
                'response' => $value,
            ]
        );

        return $response->json('success');
    }
}
