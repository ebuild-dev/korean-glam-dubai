<?php

namespace App\Livewire;

use App\Models\contacts;
use Livewire\Component;
use Livewire\WithPagination;

class ListeContact extends Component
{
    use WithPagination;

    public $key;
    public $deleted_confirmed = false;

    public function delete_client()
    {
        $this->deleted_confirmed = true;
    }

    
    public function render()
    {
        $clients = contacts::Orderby("created_at");
        if (isset($this->key)) {
            $clients->where('nom', 'like', '%' . $this->key . '%')
                ->orWhere('telephone', 'like', '%' . $this->key . '%');
        }
        $clients = $clients->paginate(60);
        $total = contacts::count();
        return view('livewire.liste-contact', compact('clients', 'total'));
    }


    public function delete_all_client()
    {
        $clients = contacts::Orderby("created_at")->get();
        foreach ($clients as $client) {
            $client->delete();
        }
        //flash message
        session()->flash('success', 'Tous les clients ont été supprimés avec succès!');
        $this->deleted_confirmed = false;
        $this->resetPage();
    }

    public function filtrer()
    {
        //reset page
        $this->resetPage();
    }

    public function delete($id)
    {
        //delete client
        $client = contacts::find($id);
        if ($client) {
            $client->delete();
            session()->flash('success', 'Client supprimé avec succès!');
        }
    }

    
}
