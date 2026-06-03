<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $expiresAt;

    /**
     * Create a new message instance.
     */
    public function __construct(string $otp, ?\DateTimeInterface $expiresAt = null)
    {
        $this->otp = $otp;
        $this->expiresAt = $expiresAt;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject('Your Lead Brand Hub OTP')
                    ->view('emails.otp')
                    ->with([
                        'otp' => $this->otp,
                        'expiresAt' => $this->expiresAt,
                    ]);
    }
}
