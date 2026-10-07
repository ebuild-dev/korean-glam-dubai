<?php

namespace App\Http\Controllers;

use App\Models\favoris;
use App\Models\produits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavorisController extends Controller
{
    public function add(Request $request)
    {
        if(!Auth::check()){
            return response()->json(
                [
                    "statut" => false,
                    "message" => "Please sign in!"
                ]
            );
        }
        $id_produit = $request->input('id_produit');
        $produit = produits::find($id_produit);
        if (!$produit) {
            return response()->json(
                [
                    "statut" => false,
                    "message" => "This product is unavailable!"
                ]
            );
        }

        $count = favoris::where('id_user', Auth::user()->id)->where("id_produit", $id_produit)->count();
        if ($count != 0) {
            return response()->json(
                [
                    "statut" => true,
                    "message" => "This product is already in your wishlist!"
                ]
            );
        }

        $favoris = new favoris();
        $favoris->id_user = Auth::user()->id;
        $favoris->id_produit = $id_produit;
        $favoris->save();
        return response()->json([
            "statut" => true,
            "message" => "Product added",
        ]);
    }
}
