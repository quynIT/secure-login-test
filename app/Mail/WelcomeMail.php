<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable;

    public $user;
    public $password;
    
    public function __construct($user,$password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: env('MAIL_FROM_ADDRESS'),
            subject: 'Chào mừng bạn đến với hệ thống!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
            with: [
                'name' => $this->user->name,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}