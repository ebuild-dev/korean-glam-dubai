<?php

namespace App\Livewire\Front;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Reset extends Component
{
    public $token,$password,$password_confirmation;

    public function mount($token){
        $this->token = $token;
    }

    public function render()
    {
        return view('livewire.front.reset');
    }

    public function reset_password(){
        $this->validate([
            'password' => ['required','min:8','confirmed'],
        ]);

        $user = User::where('token',$this->token)->first();
        if(!$user){
            session()->flash('error','Token invalide');
            return ;
        }
        $user->password =  Hash::make($this->password);
        $user->save();

        $user->update(['token'=>null]);
        auth()->logout();
        return redirect()->route('login')->with('success','Password updated successfully');;
    }
}
