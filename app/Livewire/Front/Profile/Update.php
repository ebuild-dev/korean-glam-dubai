<?php

namespace App\Livewire\Front\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Update extends Component
{
    public $nom,$email,$telephone;

    public function mount(){
        $user = Auth::user();
        if($user){
            $this->nom = $user->nom;
            $this->email = $user->email;
            $this->telephone = $user->phone;
        }
    }

    public function render()
    {
        return view('livewire.front.profile.update');
    }

public function update(){
    $this->validate([
        'nom' =>'required|string',
        'email' =>'required|email',
        'telephone' =>'required|numeric',
    ],[
        'nom.required' => 'Le nom est obligatoire',
        'email.required' => 'L\'adresse e-mail est obligatoire',
        'email.email' => 'Veuillez entrer une adresse e-mail valide',
        'telephone.required' => 'Le numéro de téléphone est obligatoire',
        'telephone.numeric' => 'Veuillez entrer un numéro de téléphone valide'
    ]);
    $logout = false;
    $user = Auth::user();
    $user->nom = $this->nom;
    $user->phone = $this->telephone;
    if($this->email != $user->email){
        $count =User::where('email',$this->email)->count();
        if($count > 0){
            session()->flash('error', 'This email address is already in use');
            return redirect()->back();
        }else{
            $user->email = $this->email;
            $logout = true;
        }
    }
    $user->save();

    if($logout){
        Auth::logout();
        return redirect()->route('login')->with('success', 'Your details have been updated successfully');
    }else{
        session()->flash('success', 'Your details have been updated successfully');
        return;
    }


}

}
