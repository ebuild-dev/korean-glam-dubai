<?php

namespace App\Livewire\Front;

use App\Models\contacts;
use Livewire\Component;

class ContactForm extends Component
{
    public $nom,$email,$telephone,$message;
    public $end = false;

    public function render()
    {
        return view('livewire.front.contact-form');
    }


    public function save(){
        $this->validate([
            'nom' =>'required|string',
            'email' =>'required|email',
            'telephone' =>'required|numeric',
            'message' =>'required|string'
        ],[
            'nom.required' => 'Veuillez entrer votre nom',
            'email.required' => 'Veuillez entrer votre email',
            'email.email' => 'Veuillez entrer un email valide',
            'telephone.required' => 'Veuillez entrer votre numéro de téléphone',
            'message.required' => 'Please enter your message'
            
        ]);

        $contact = new contacts();
        $contact->nom = $this->nom;
        $contact->email = $this->email;
        $contact->telephone = $this->telephone;
        $contact->message = $this->message;
        $contact->save();

        // Envoi du message à votre service de messagerie ici
        // Exemple : Mail::to('admin@yourdomain.com')->send(new ContactMessage($this->attributes));
        

        session()->flash('message', 'Your message has been sent!');
        $this->end = true;
    }

}
