<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Mail;

use Juzaweb\Modules\Core\Mail\Test;
use Juzaweb\Modules\Core\Tests\TestCase;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;

class TestTest extends TestCase
{
    public function test_envelope_returns_correct_subject()
    {
        $mail = new Test();
        $envelope = $mail->envelope();

        $this->assertInstanceOf(Envelope::class, $envelope);
        $this->assertEquals('Juzaweb Test Mail', $envelope->subject);
    }

    public function test_content_returns_correct_view()
    {
        $mail = new Test();
        $content = $mail->content();

        $this->assertInstanceOf(Content::class, $content);
        $this->assertEquals('core::mail.test', $content->markdown);
    }
}
