<?php

namespace App\Livewire\Packs;

use App\Models\packs;
use Livewire\Component;

class Liste extends Component
{
    public function render()
    {
        $packs = packs::all();
        return view('livewire.packs.liste', compact("packs"));
    }

    public function delete($id)
    {
        $packs = packs::find($id);
        if ($packs) {
            $packs->delete();
            session()->flash('success', 'Pack supprimé avec succès');
        }
    }
}
