<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable implements ShouldQueue
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
            subject: 'Thông báo reset mật khẩu',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reset',
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