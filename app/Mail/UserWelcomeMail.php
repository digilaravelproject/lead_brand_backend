<?php

namespace App\Mail;

use App\Models\Admin;
use App\Models\Dealer;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public Dealer|Admin $owner) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Welcome to AdvisorX Pro - your 4-day trial is active');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.user-welcome');
    }
}
