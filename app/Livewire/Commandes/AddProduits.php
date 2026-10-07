<?php

namespace App\Livewire\Commandes;

use App\Models\commandes;
use App\Models\contenu_commande;
use App\Models\produits;
use Livewire\Component;

class AddProduits extends Component
{

    public $produits = [];
    public $reference, $quantite = 1, $id_commande,$key,$listes;

    public function mount($id_commande)
    {
        $this->id_commande = $id_commande;
    }

    public function updatedkey($value){
        if($value){
            $this->listes = produits::select( 'nom','reference','prix')
                ->where('nom', 'like', '%'. $value. '%')
                ->orWhere('reference', 'like', '%'. $value. '%')
                ->take(5)
                ->get();
        }
    }

    public function ajouter($reference){
        $this->reference = $reference;
        $this->listes = null;
        $this->key = null;
    }

    public function render()
    {
        return view('livewire.commandes.add-produits');
    }


    public function save()
    {
        $this->validate(
            [
                'reference' => 'required|exists:produits,reference',
                'quantite' => 'required|integer|gt:0'
            ]
        );

        //verifier que le produit existe
        $produit = produits::find($this->reference);

        if (!$produit) {
            session()->flash('reference', "Produit introuvable!");
            return;
        }


        //si sa existe on change la quantité par la nouvelle quantié
        $key = array_search($this->reference, array_column($this->produits, 'reference'));
        if ($key !== false) {
            $this->produits[$key]['quantite'] = $this->quantite;
        } else {
            $this->produits[] = ['reference' => $this->reference, 'quantite' => $this->quantite];
        }
        //reset input
        $this->reference = "";
        $this->quantite = "";
    }

    public function delete($reference)
    {
        $key = array_search($reference, array_column($this->produits, 'reference'));
        if ($key !== false) {
            unset($this->produits[$key]);
        }
    }


    public function Terminer()
    {
        $commande = commandes::find($this->id_commande);
        $produits = $this->produits;
        foreach ($produits as $item) {

            $produit = produits::where('reference', $item['reference'])->first();
            if ($produit) {
                $contenu_commande = new contenu_commande();
                $contenu_commande->id_commande = $commande->id;
                $contenu_commande->id_produit = $produit->id;
                $contenu_commande->quantite = $item['quantite'];
                $contenu_commande->prix_unitaire = $produit->getPrice();
                $contenu_commande->benefice = ($produit->getPrice() - $produit->getPrixVente()) * $item['quantite'];
                $contenu_commande->prix_total = $produit->getPrice()  * $item['quantite'];
                $contenu_commande->save();
            }
        }

        //reload page
        $this->redirect(route('edit_commande',['id'=>$commande->id]));
    }
}
