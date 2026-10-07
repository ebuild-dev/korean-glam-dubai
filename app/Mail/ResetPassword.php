<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->view('Mail.forget')
            ->subject('Nouveau mot de passe'.config('app.name'))
            ->from("tunisie@korean-glam.com", "réinitialiser votre mot de passe");

    }
}
