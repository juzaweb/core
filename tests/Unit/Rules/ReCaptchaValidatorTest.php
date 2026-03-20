<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Rules;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Juzaweb\Modules\Core\Rules\ReCaptchaValidator;
use Juzaweb\Modules\Core\Tests\TestCase;

class ReCaptchaValidatorTest extends TestCase
{
    public function test_validate_returns_true_on_success()
    {
        Config::set('services.recaptcha.secret', 'test-secret');

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response(['success' => true], 200),
        ]);

        $validator = new ReCaptchaValidator;
        $result = $validator->validate('recaptcha', 'test-token', [], null);

        $this->assertTrue($result);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://www.google.com/recaptcha/api/siteverify' &&
                   $request['secret'] === 'test-secret' &&
                   $request['response'] === 'test-token';
        });
    }

    public function test_validate_returns_false_on_failure()
    {
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response(['success' => false], 200),
        ]);

        $validator = new ReCaptchaValidator;
        $result = $validator->validate('recaptcha', 'invalid-token', [], null);

        $this->assertFalse($result);
    }
}
