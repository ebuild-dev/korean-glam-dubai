<?php

namespace App\Http\Controllers;

use App\Models\config;
use App\Models\produits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class PanierController extends Controller
{
    public function add(Request $request)
    {
        $id_produit = $request->input('id_produit');
        $type = $request->input('type', 'produit');
        $quantite = $request->input('quantite', 1);
        $user = Auth::user();
        $produit = produits::find($id_produit);


        //verifier que le produit existe et est disponible
        if (!$produit) {
            return response()->json([
                'statut' => false,
                'message' => "Product not found!",
            ]);
        }

        if ($produit->stock < $quantite) {
            return response()->json([
                'statut' => false,
                'message' => "This product is out of stock!",
            ]);
        }




        $panier = session('panier_front', []);
        $produit_existe = false;

        foreach ($panier as &$item) {
            if ($item['id_produit'] == $id_produit) {
                $item['quantite'] += $quantite;
                $produit_existe = true;
                break;
            }
        }

        if (!$produit_existe) {
            $panier[] = [
                'id_produit' => $id_produit,
                'quantite' => $quantite,
                'type' => $type,
            ];
        }

        session(['panier_front' => $panier]);

        return response()->json([
            'statut' => true,
            'message' => "Product added",
            'produit' => $produit,
        ]);
    }
    public function preorder(Request $request)
    {
        $quantite = $request->input('quantite', 1);

        $produit = Produits::find($request->id_produit);
        $type = $request->input('type', 'produit');

        if (!$produit) {
            return response()->json([
                'statut' => false,
                'message' => "Product not found",
            ]);
        }

        $panier = session('panier_front', []);
        $produit_existe = false;

        foreach ($panier as &$item) {
            if ($item['id_produit'] ==   $produit->id) {
                $item['quantite'] += $quantite;
                $produit_existe = true;
                break;
            }
        }

        if (!$produit_existe) {
            $panier[] = [
                'id_produit' => $produit->id,
                'quantite' => $quantite,
                'type' => $type,
            ];
        }

        session(['panier_front' => $panier]);
        return response()->json([
            'statut' => true,
            'produit' => $produit,
            'message' => "added as preorder"
        ]);
    }


    public function count_panier()
    {        $configs= config::first();


        // Vérifier si la session 'panier_front' existe, sinon initialiser une session vide
        if (!session()->has('panier_front')) {
            session(['panier_front' => []]);
        }
        // Récupérer le panier de la session
        $panier_temporaire = session('panier_front');
        $total = count($panier_temporaire);
        $produits = [];
        $total_prix = 0;
        $total_prix_with_shipping=0;

        foreach ($panier_temporaire as $pan) {
            $produit = produits::find($pan['id_produit']);
            if ($produit) {
                $produits[] = [
                    "produit_id" => $produit->id,
                    "nom" => Str::limit($produit->nom, 20),
                    "prix" => $produit->getPrice(),
                    "photo" => $produit->FirstImage(),
                    "quantite" => $pan['quantite'],
                    "total" => $produit->getPrice() * $pan['quantite'],
                    "en_promotion" => $produit->inPromotion(),
                    "prix_before" => $produit->prix,
                ];
                $total_prix += $produit->getPrice() * $pan['quantite'];
                $total_prix_with_shipping= $total_prix + $configs->frais;

            }
        }
        return response()->json(
            [
                "total" => $total,
                "total_prix" => $total_prix,
                "produits" => $produits,
            ]
        );
    }



    public function deleteElement($produit_id)
{
    $panier = session('panier_front', []);
    foreach ($panier as $key => $item) {
        if ($item['id_produit'] == $produit_id) {
            unset($panier[$key]);
            session(['panier_front' => $panier]);
            return response()->json([
                'statut' => true,
                'message' => 'Item removed successfully.'
            ]);
        }
    }

    return response()->json([
        'statut' => false,
        'message' => 'Product not found in your cart.'
    ], 404);
}


public function updateQuantite($produit_id, $quantite)
{
    $panier = session('panier_front', []);
    foreach ($panier as $key => $item) {
        if ($item['id_produit'] == $produit_id) {
            if (!is_numeric($quantite) || $quantite <= 0) {
                return response()->json([
                    'statut' => false,
                    'message' => 'Quantity must be a positive number.'
                ], 400);
            }
            $panier[$key]['quantite'] = (int)$quantite;
            session(['panier_front' => $panier]);
            return response()->json([
                'statut' => true,
                'message' => 'Quantity updated successfully.'
            ]);
        }
    }
    return response()->json([
        'statut' => false,
        'message' => 'Product not found in your cart.'
    ], 404);
}





public function clearPanier(){
    session()->forget('panier_front');
    return redirect()
    ->route('cart')
    ->with('success', "Your cart has been emptied!");
}


}
