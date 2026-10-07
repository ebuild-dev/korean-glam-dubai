<?php

namespace App\Livewire\Packs;

use App\Models\contenu_pack;
use App\Models\packs;
use App\Models\produits;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Add extends Component
{
    use WithPagination;
    public $nom, $prix, $reference, $key, $propositions = [];
    public $produits = [];

    public function mount()
    {
        // Initialiser le panier dans la session si ce n'est pas déjà fait
        if (!Session::has('cart')) {
            Session::put('cart', []);
        }
    }

    public function render()
    {
        $panier = Session::get('cart', []);
        return view('livewire.packs.add', compact("panier"));
    }

    public function save()
    {
        // Validation
        $this->validate([
            'nom' => 'required|string',
            'prix' => 'required|numeric',
            'reference' => 'required|string|unique:packs,reference',
        ], [
            'nom.required' => 'Le nom du pack est obligatoire',
            'prix.required' => 'Le prix du pack est obligatoire',
            'prix.numeric' => 'Le prix du pack doit être un nombre',
            'reference.required' => 'La référence du pack est obligatoire',
            'reference.unique' => 'La référence du pack est déjà utilisée',
        ]);

        $panier = Session::get('cart', []);
        if (empty($panier)) {
            // Flash error message
            session()->flash('error', 'Veuillez ajouter au moins un produit');
            return;
        }

        $pack = new packs();
        $pack->nom = $this->nom;
        $pack->prix = $this->prix;
        $pack->by = Auth::user()->id;
        $pack->reference = $this->reference;
        if ($pack->save()) {
            foreach ($panier as $item) {
                $contenu = new contenu_pack();
                $contenu->id_pack = $pack->id;
                $contenu->id_produit = $item['id'];
                $contenu->quantite = $item['quantity'];
                $contenu->save();
            }
        }

        // Effacer le panier de la session
        Session::forget('cart');
        // Flash success message
        return redirect()->route('packs')->with('success', 'Pack ajouté avec succès');
    }

    public function recherche()
    {
        $value = $this->key;
        if ($value != "") {
            $produits = produits::orderBy("created_at");
            $produits->where('nom', 'like', '%' . $value . '%')
                ->orWhere('reference', 'like', '%' . $value . '%');
            $this->propositions = $produits->get();
        } else {
            $this->propositions = [];
        }
    }

    public function add_cart()
    {
        $cartData = Session::get('cart', []);
    
        // Préparer les nouvelles données des produits à ajouter au panier
        foreach ($this->produits as $id => $quantite) {
            $produit_bd = produits::find($id);
            if ($produit_bd) {
                $found = false;
                // Vérifier si le produit existe déjà dans le panier
                foreach ($cartData as &$item) {
                    if ($item['id'] == $produit_bd->id) {
                        // Mettre à jour la quantité du produit existant
                        $item['quantity'] = $quantite;
                        $found = true;
                        break;
                    }
                }
                // Si le produit n'existe pas dans le panier, l'ajouter
                if (!$found) {
                    $cartData[] = [
                        'id' => $produit_bd->id,
                        'name' => $produit_bd->nom,
                        'quantity' => $quantite ?? 1,
                        'price' => $produit_bd->prix,
                    ];
                }
            }
        }

        // Mettre à jour la session avec les nouvelles données du panier
        Session::put('cart', $cartData);

        // Afficher un message de succès
        session()->flash('success', 'Produit ajouté au panier');
    }

    public function delete_from_cart($id)
    {
        $cartData = Session::get('cart', []);
        $cartData = array_filter($cartData, function ($item) use ($id) {
            return $item['id'] != $id;
        });
        Session::put('cart', array_values($cartData));
    }
    
}
