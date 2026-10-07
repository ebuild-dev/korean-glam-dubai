<?php

namespace App\Livewire\Categories;

use App\Models\Souscategorie;
use Livewire\Component;
use App\Models\SousSousCategorie;
use App\Models\categories;
use App\Models\produits;

class SousSousCategories extends Component
{
    public $nom, $id_categorie, $id_souscategorie, $id_produit, $order_column;

    protected $listeners = ['closeUpdateSousSousCategorie' => '$refresh'];

    public function render()
    {
        $sous_souscategories = SousSousCategorie::orderBy("order_column", "asc")->get();
        $categories = categories::orderBy("order_column", "asc")->get();
        $souscategories = Souscategorie::orderBy("order_column", "asc")->get();
        $produits = produits::orderBy("id", "asc")->get();

        return view('livewire.categories.sous-sous-categorie')
            ->with('sous_souscategories', $sous_souscategories)
            ->with('categories', $categories)
            ->with('souscategories', $souscategories)
            ->with('produits', $produits);
    }
    public function save()
    {
        $this->validate([
            'nom' => 'required|string|min:3|max:255',
            'id_categorie' => 'nullable|exists:categories,id',
            'id_souscategorie' => 'nullable|exists:souscategories,id',
            'id_produit' => 'nullable|exists:produits,id',
            'order_column' => 'nullable|integer',
        ]);

        SousSousCategorie::create([
            'nom' => $this->nom,
            'id_categorie' => $this->id_categorie,
            'id_souscategorie' => $this->id_souscategorie,
            'id_produit' => $this->id_produit,
            'order_column' => $this->order_column,
        ]);

        $this->reset(['nom', 'id_categorie', 'id_souscategorie', 'id_produit', 'order_column']);

        session()->flash('success', 'La sous-sous-catégorie a été créée avec succès');
    }
    public function delete($id)
    {
        if ($ssc = SousSousCategorie::find($id)) {
            $ssc->delete();
            session()->flash('success', 'Sous-sous-catégorie supprimée avec succès!');
        }
    }
}
