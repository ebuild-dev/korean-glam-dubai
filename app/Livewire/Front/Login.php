<?php

namespace App\Livewire\Front;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email,$password;

    public function render()
    {
        return view('livewire.front.login');
    }


    public function login()
    {
        $this->validate(
            [
                'email' => 'required|email|exists:users,email',
                'password' => 'required',
            ],[
                'email.required' => 'Veuillez entrer votre email',
                'email.email' => 'Veuillez entrer un email valide',
                'email.exists' => 'Cet email n\'existe pas',
                'password.required' => 'Veuillez entrer votre mot de passe',
                'password.min' => 'Votre mot de passe doit contenir au moins 8 caractères',
                'password.confirmed' => 'Les mots de passe ne sont pas identiques',
            ]
        );

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $user = Auth::user();
            if ($user->role == 'client') {
                return redirect()->route('home');
            } else {
                return redirect()->route('dashboard');
            }
        } else {
            session()->flash('error', 'Your login details are incorrect!');
        }
    }

}
