<?php

namespace Juzaweb\Modules\Core\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class Test extends Mailable
{
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Juzaweb Test Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'core::mail.test',
        );
    }
}
