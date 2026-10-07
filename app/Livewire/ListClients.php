<?php

namespace App\Livewire;

use App\Models\clients;
use App\Models\config;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ListClients extends Component
{
    use WithPagination;

    public $key;
    public $deleted_confirmed = false;



    public $confirmingDeleteId = null;
    public function delete_client()
    {
        $this->deleted_confirmed = true;
    }

    public function render()
    {
        $config=config::first();
        $clients = \App\Models\User::with(['guides', 'commandes.contenus.produit' => function ($query) {
            $query->select('produits.id', 'produits.nom', 'produits.prix');
        }, 'commandes' => function($q) {
            $q->orderBy('created_at', 'desc');
        }])
            ->where('role', "client")
            ->orderBy("created_at", "desc");

        if (!empty($this->key)) {
            $clients->where(function($query) {
                $query->where('nom', 'like', '%' . $this->key . '%')
                    ->orWhere('phone', 'like', '%' . $this->key . '%')
                    ->orWhere('zipcode', 'like', '%' . $this->key . '%');
            });
        }

        $clients = $clients->paginate(60);
        $total = \App\Models\User::where('role', "client")->count();

        return view('livewire.list-clients', compact('clients', 'total','config'));
    }



    public function delete_all_client()
    {
        $clients = User::Orderby("created_at")->where('role', "client")->get();
        foreach ($clients as $client) {
            $client->update(
                [
                    'email' => $client->email . '-deleted-' . time(),
                ]
            );
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

  /*  public function delete($id)
    {
        //delete client
        $client = User::find($id);
        if ($client) {
            $client->update(
                [
                    'email' => $client->email . '-deleted-' . time(),
                ]
            );
            $client->delete();
            //flash message
            session()->flash('success', 'Client supprimé avec succès!');
        }
    }*/
    public function confirmDelete($id)
    {
        $client = User::where('role', 'client')->find($id);

        if (!$client) {
            session()->flash('error', 'Client introuvable.');
            return;
        }

        $this->confirmingDeleteId = $id;
    }

    public function cancelDelete()
    {
        $this->confirmingDeleteId = null;
    }

    public function delete($id)
    {
        $client = User::where('role', 'client')->find($id);

        if (!$client) {
            session()->flash('error', 'Client introuvable.');
            $this->confirmingDeleteId = null;
            return;
        }

        $client->update([
            'email' => $client->email . '-deleted-' . time(),
        ]);

        $client->delete();

        $this->confirmingDeleteId = null;

        session()->flash('success', 'Client supprimé avec succès !');
    }
}
