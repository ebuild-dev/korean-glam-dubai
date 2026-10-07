<?php

namespace App\Mail;

use App\Models\commandes;
use App\Models\config;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Commande extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $id_commande,$commande,$config;
    public function __construct($id_commande,$config)
    {
        $this->id_commande = $id_commande;
        $this->config = $config;
    }


    public function build()
    {
        $this->commande = commandes::find($this->id_commande);
        $this->config = config::first();
        return $this->to($this->commande->email)
            ->view('Mail.commande')
            ->subject('Votre commande sur '. config('app.name'))
            ->from("tunisie@korean-glam.com", config('app.name'));

    }

}
