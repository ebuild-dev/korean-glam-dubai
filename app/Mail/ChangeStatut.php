<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ChangeStatut extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $commande;
    public function __construct($commande)
    {
        $this->commande = $commande;
    }

    public function build()
    {
        return $this->to($this->commande->client->email)
            ->view('Mail.change-statut')
            ->subject('Mise à Jour du Statut de Commande sur '. config('app.name'))
            ->from("tunisie@korean-glam.com", config('app.name'));

    }
}
