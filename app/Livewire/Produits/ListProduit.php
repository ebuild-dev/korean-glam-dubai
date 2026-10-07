<?php

namespace App\Livewire\Produits;

use App\Models\categories;
use App\Models\produits;
use App\Models\Souscategorie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class ListProduit extends Component
{

    protected $listeners = ['add-stock' => '$refresh','refreshComponent' => '$refresh','reorderProduits'];
    use WithPagination;
    public $key;
    public $id_categorie;
    public $id_sous_categorie;

    public $confirmingDeleteAll = false; // ⚠️ Doit être public
    public $selectedProducts = [];
    public $selectAll = false;
    public $confirmingDeleteSelected = false;
    protected $paginationTheme = 'bootstrap';
    public $brand;
    public function mount()
    {
        if (session()->has('produit_filters')) {
            $filters = session('produit_filters');
            $this->key = $filters['key'] ?? '';
            $this->id_categorie = $filters['id_categorie'] ?? '';
            $this->id_sous_categorie = $filters['id_sous_categorie'] ?? '';
            $this->brand = $filters['brand'] ?? '';
        }
    }
    public function toggleExclusive($id)
    {
        $produit = \App\Models\produits::findOrFail($id);
        $produit->exclusive = ! $produit->exclusive;
        $produit->save();
    }


    public function confirmDeleteSelected()
    {
        if (count($this->selectedProducts) > 0) {
            $this->confirmingDeleteSelected = true;
        } else {
            session()->flash('error', 'Aucun produit sélectionné.');
        }
    }

    public function deleteSelected()
    {
        produits::whereIn('id', $this->selectedProducts)->delete();
        $this->selectedProducts = [];
        $this->selectAll = false;
        $this->confirmingDeleteSelected = false;

        session()->flash('message', 'Les produits sélectionnés ont été supprimés avec succès.');
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedProducts = produits::pluck('id')->toArray();
        } else {
            $this->selectedProducts = [];
        }
    }

    public function confirmDeleteAll()
    {
        $this->confirmingDeleteAll = true;
    }

    public function deleteAll()
    {
        try {
            // Supprimer tous les produits
            produits::query()->delete();

            // Message de succès
            session()->flash('success', '✅ Tous les produits ont été supprimés avec succès !');

            // Réinitialiser l’état du bouton de confirmation
            $this->confirmingDeleteAll = false;

            // Rafraîchir la liste
            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', '❌ Erreur : ' . $e->getMessage());
        }
    }
    public function render()
    {
        $query = produits::query()
            ->orderByRaw("order_column = 0 ASC") // 0 en dernier
            ->orderBy('order_column', 'asc');
        if (!empty($this->key)) {
            $query->where('nom', 'like', '%' . $this->key . '%');
        }

        if (!empty($this->id_categorie)) {
            $query->where('id_categorie', $this->id_categorie);
        }

        if (!empty($this->id_sous_categorie)) {
            $query->where('id_sous_categorie', $this->id_sous_categorie);
        }

        // une seule condition suffit
        if ($this->brand !== null && $this->brand !== '') {
            $query->where('id_brand', $this->brand);
        }

        $produits = $query
            ->with(['sous_categorie:id,nom', 'categorie:id,nom', 'marque:id,brandname', 'promotion'])
            ->withCount('vendus')
            ->paginate(60);

        $total = produits::count();
        $total_supprimers = produits::onlyTrashed()->count();
        $categories = categories::select('id', 'nom', 'order_column')->orderBy('order_column')->get();
        $sous_categories = Souscategorie::select('id', 'nom', 'order_column')->orderBy('order_column')->get();
        $brands = \App\Models\Brand::select('id', 'brandname')->orderBy('brandname')->get();

        return view('livewire.produits.list-produit', compact(
            'produits','total','total_supprimers','categories','sous_categories','brands'
        ));
    }


    public function delete($id)
    {
        $produit = produits::find($id);

        if (!$produit) {
            session()->flash('error', 'Produit introuvable.');
            return;
        }

        $produit->delete();

        session()->flash('success', 'Produit supprimé avec succès.');
    }





    public function filtrer()
    {
        session([
            'produit_filters' => [
                'key' => $this->key,
                'id_categorie' => $this->id_categorie,
                'id_sous_categorie' => $this->id_sous_categorie,
                'brand' => $this->brand,
            ]
        ]);
        //reset page
        $this->resetPage();
    }
}
