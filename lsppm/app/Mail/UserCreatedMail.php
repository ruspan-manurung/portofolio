<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $rawPassword;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $rawPassword)
    {
        $this->user = $user;
        $this->rawPassword = $rawPassword;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Akun Anda Berhasil Dibuat')
                    ->view('emails.user_created');
    }
}