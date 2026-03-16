<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountCreatedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public User $user,
        public string $plainPassword,
        public string $loginUrl
    )
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Votre compte JEFAL Prive a ete cree');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.account-created');
    }
}
