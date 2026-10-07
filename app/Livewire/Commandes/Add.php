<?php

namespace App\Livewire\Commandes;

use App\Models\produits;
use Livewire\Component;

class Add extends Component
{
    public $produits = [];
    public $key, $id_select_produit, $quantite = 1;

    public function updatedKey($value)
    {
        $this->key = $value;
        $this->id_select_produit = null;
        $produits = produits::select('id', 'nom', 'photo', 'reference','prix')
            ->where('nom', 'like', '%' . $this->key . '%')
            ->Orwhere('reference', 'like', '%' . $this->key . '%')
            ->take(5)
            ->get();
        $result = [];
        // Ajouter les produits au tableau result avec le type 
        foreach ($produits as $produit) {
            $result[] = [
                'id' => $produit->id,
                'photo' => $produit->FirstImage(),
                'nom' => $produit->nom,
                'prix' => $produit->prix,
                'reference' => $produit->reference,
            ];
        }

        $this->produits = $result;
    }

    public function ajouterProduit($id_produit)
    {
        $this->id_select_produit = $id_produit;
        $this->produits = [];
        $this->key = "";
    }


    public function reset_add(){
        $this->id_select_produit = "";
        $this->produits = [];
        $this->key = "";
    }


    public function render()
    {
        return view('livewire.commandes.add');
    }



    public function save()
    {
        $this->validate([
            'quantite' => 'required|integer|min:1',
            'id_select_produit' => 'required|integer|exists:produits,id'
        ], [
            'quantite.required' => 'Veuillez entrer une quantité',
            'quantite.integer' => 'Veuillez entrer une quantité valide',
            'quantite.min' => 'La quantité doit être au minimum 1',
            'id_select_produit.required' => 'Veuillez sélectionner un produit',
            'id_select_produit.integer' => 'Veuillez sélectionner un produit valide',
            'id_select_produit.exists' => 'Ce produit n\'existe pas'
        ]);


        $article = produits::find($this->id_select_produit);
        $quantite = $this->quantite;

        if ($quantite > $article->stock) {
            session()->flash('error', "La quantité demandée pour l'article ' $article->nom ' est indisponible.");
            return;
        }


        //verifier si le produit nest pas deja dans le panier
        $cartData = session()->get('panier', []);
        $found = false;
        foreach ($cartData ?? [] as $item) {
            if ($item['id'] == $article->id) {
                // Mettre à jour la quantité du produit existant
                $item['quantite'] += $quantite;
                $found = true;
                break;
            }
        }
        // Si le produit n'existe pas dans le panier, l'ajouter
        if (!$found) {

            // verifier que in ya pas une condition de prix par quantiter sur cet article
            if ($article->autres_prix()) {
                $total = $article->getPrice_with_autre_prix($quantite);
            } else {
                $total = $quantite * $article->getPrice();
            }

            $cartData[] = [
                'id' => $article->id,
                'quantite' => intval($quantite),
                'nom' => $article->nom,
                'prix' => $article->getPrice_with_autre_prix($quantite),
                'reference' => $article->reference,
                'total' => $total ?? 0,
            ];
        }
        session()->put('panier', $cartData);
        session()->flash('success', "L'article ' $article->nom ' a été ajouté au panier.");
        $this->key = "";
        $this->id_select_produit = null;
        $this->dispatch('ProduitAdded');
    }

}
