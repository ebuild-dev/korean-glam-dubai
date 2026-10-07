<?php

namespace App\Livewire\Front\Favoris;

use App\Models\favoris;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Liste extends Component
{
    public function render()
    {
        $user =Auth::user();

        $favoris = favoris::where('id_user',$user->id)->get();
        return view('livewire.front.favoris.liste')
        ->with('favoris', $favoris);
    }

    public function delete($id){
        $favori = favoris::find($id);
        if ($favori) {
            $favori->delete();
            session()->flash('success', 'Item removed from your wishlist');
        }
    }
}
