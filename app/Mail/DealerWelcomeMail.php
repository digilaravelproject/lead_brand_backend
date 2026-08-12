<?php

namespace App\Mail;

use App\Models\Dealer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DealerWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Dealer $dealer, public string $plainPassword) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Your AdvisorX Pro dealer account');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.dealer-welcome');
    }
}
