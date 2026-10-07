<?php

namespace App\Livewire\Categories;

use App\Models\Souscategorie;
use App\Models\SousSousCategorie;
use App\Models\categories;

use App\Models\produits;
use Livewire\Component;
class UpdateSousSousCategorie extends Component
{
    public $soussousId, $soussous;
    protected $listeners = ['openUpdateSousSousCategorie' => 'open'];

    public $nom, $id_categorie, $id_souscategorie, $id_produit;

    public function open($id)
    {
        $this->soussousId = $id;
        $this->soussous = SousSousCategorie::find($id);

        if ($this->soussous) {
            $this->nom = $this->soussous->nom;
            $this->id_categorie = $this->soussous->id_categorie;
            $this->id_souscategorie = $this->soussous->id_souscategorie;
            $this->id_produit = $this->soussous->id_produit;
        }
    }
    public function render()
    {
        $categories = categories::orderBy('order_column', 'asc')->get();
        $souscategories = Souscategorie::orderBy('order_column', 'asc')->get();
        $produits = produits::orderBy('id', 'asc')->get();

        return view('livewire.categories.update-sous-sous-categorie')
            ->with('categories', $categories)
            ->with('souscategories', $souscategories)
            ->with('produits', $produits);
    }
    public function UpdateSousSousCategorie()
    {
        $this->validate([
            'nom' => 'required|string|min:2|max:255',
            'id_categorie' => 'nullable|exists:categories,id',
            'id_souscategorie' => 'nullable|exists:souscategories,id',
            'id_produit' => 'nullable|exists:produits,id',
        ]);

        $soussous = SousSousCategorie::find($this->soussousId);
        $soussous->nom = $this->nom;
        $soussous->id_categorie = $this->id_categorie;
        $soussous->id_souscategorie = $this->id_souscategorie;
        $soussous->id_produit = $this->id_produit;
        $soussous->save();

        $this->dispatch('closeUpdateSousSousCategorie');
        session()->flash('success', 'Sous-sous-catégorie modifiée avec succès');
    }
}
