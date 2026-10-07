<?php

namespace App\Livewire\Front;

use App\Models\config;
use App\Models\produits;
use Livewire\Component;

class Panier extends Component
{
    public function render()
    {
        $config = config::first();
        if (!session()->has('panier_front')) {
            session(['panier_front' => []]);
        }
        // Récupérer le panier de la session
        $panier_temporaire = session('panier_front');
        $panier = [];
        $montant = 0;
        $frais = 0;
        $add_frais = true;
        $montant_final = 0;
        foreach ($panier_temporaire as $key => $item) {
            $produit = produits::find($item['id_produit']);
            if ($produit) {
                if($produit->frais_inclu == 1 ){
                    $add_frais = false;
                }
                $panier[] = [
                    "photo" => $produit->FirstImage(),
                    "nom" => $produit->nom,
                    "quantite" => $item['quantite'],
                    "prix" => $produit->getPrice(),
                    "id" => $key,
                    "id_produit" => $produit->id,
                    "total" =>  $produit->getPrice() * $item['quantite']
                ];
                $montant +=  $produit->getPrice() * $item['quantite'];
            }
        }

        if ($add_frais) {
            $frais =  $config->frais ?? 0;
        }
        $tva = $config->tva ?? 0;
        $timbre = $config->timbre ?? 0;
        $montant_final = $montant  + $frais ;

        return view('livewire.front.panier')
            ->with('panier', $panier)
            ->with('montant', $montant)
            ->with('config', $config)
            ->with('frais_livraison', $frais)
            ->with('montant_final', $montant_final)
            ->with('timbre', $timbre)
            ->with('frais', $frais);
    }

    public function delete($id)
    {
        $panier_temporaire = session('panier_front');
        unset($panier_temporaire[$id]);
        session(['panier_front' => $panier_temporaire]);
    }


    public function update($key, $new_qte)
    {

        if ($new_qte <= 0) {
            $this->delete($key);
            return;
        }

        $panier_temporaire = session('panier_front');
        $panier_temporaire[$key]['quantite'] = $new_qte;
        session(['panier_front' => $panier_temporaire]);
    }
}
