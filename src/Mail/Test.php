<?php

namespace Juzaweb\Core\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Test extends Mailable
{
    use SerializesModels;

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
