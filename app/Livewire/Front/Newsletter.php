<?php

namespace App\Livewire\Front;

use App\Models\newsletters;
use Livewire\Component;

class Newsletter extends Component
{
    public $email;
    public $end = false;
    
    public function render()
    {
        return view('livewire.front.newsletter');
    }

    public function save(){
        $this->validate([
            'email' => 'required|email|unique:newsletters,email'
        ],[
            'email.required' => 'Veuillez entrer votre adresse email',
            'email.email' => 'Veuillez entrer une adresse email valide',
            'email.unique' => 'Cette adresse email est déjà enregistrée'
        ]);
        newsletters::create(['email' => $this->email]);
        $this->end = true;
        $this->email = null;
    }

}
