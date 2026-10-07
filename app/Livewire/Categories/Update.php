<?php

namespace App\Livewire\Categories;

use App\Models\categories;
use Livewire\Component;
use Livewire\WithFileUploads;

class Update extends Component
{
    use WithFileUploads;
    public $categorieId,$categorie;
    protected $listeners = ['openUpdateCategorie' => 'open'];
    public $nom,$description,$photo;

    public function open($id)
    {
        $this->categorieId = $id;
        $this->categorie = categories::find($id);
        if($this->categorie){
            $this->nom = $this->categorie->nom;
            $this->description = $this->categorie->description;
        }
    }

    public function render()
    {
        return view('livewire.categories.update');
    }


    public function UpdateCategorie(){
        $this->validate([
            'nom' =>'required|string',
            'description' =>'required|string',
            'photo' =>'nullable|image|max:2048',
        ]);

        $categorie = categories::find($this->categorieId);
        $categorie->nom = $this->nom;
        $categorie->description = $this->description;
        if ($this->photo) {
            $categorie->photo = $this->photo->store('categories','public');
        }
        $categorie->save();

        $this->dispatch('closeUpdateCategorie');
        session()->flash('success','Categorie modifiée avec succès');
    }

}
