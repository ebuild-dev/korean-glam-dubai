<?php

namespace App\Http\Controllers;

//require './vendor/autoload.php';


use App\Models\commandes;
use App\Models\config;
use App\Models\produits;
use App\Models\templates;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{

    public function Not_Autorized()
    {
        $template = templates::where('meta_error', true)->first();
        $meta = $template->meta ?? "";
        return view("front.Not_Autorized", compact("meta"));
    }


    public function set_cookie($pays)
    {
        $minutes = 600000;
        $cookie = Cookie::make('countryName', $pays, 30, null, null, false, false);
        Cookie::queue($cookie);
        return redirect()->back()->with('success', 'Country selected!');
    }



    public function view_produit($id)
    {
        $produit = produits::find($id);
        if (!$produit) {
            abort('404');
        }
        $Html = view('components.modal-view-produit', ['produit' => $produit, 'show' => true])->render();
        return response()->json($Html);
    }



    public function print_commande($id)
    {
        $frais_livraison=0;
        $commande = commandes::find($id);
        if (!$commande) {
            abort('404');
        }
        $config = config::first();
        $frais_livraison=$config->frais;
        $data_show = $this->prepareCommande($commande);
        $pdf = PDF::loadView('pdf.commande', compact('commande', 'config', 'data_show','frais_livraison'));
        return $pdf->download("Facture-#" . $commande->id . ".pdf");
    }
    public function print_commande2($token)
    {
        $commande = commandes::where('token', $token)->first();
        if (!$commande) {
            abort('404');
        }
        $config = config::first();
        if ($commande->devise == "DT") {
        } else {
        }
        $data_show = $this->prepareCommande($commande);
        $pdf = PDF::loadView('pdf.commande_th', compact('commande', 'config', 'data_show'));
        return $pdf->download("Facture-#" . $commande->id . ".pdf");
    }



    public function print_bordereau(Request $request)
    {
        $ids = json_decode($request->get('ids'));
        $pdf = PDF::loadView('pdf.bordereau', compact("ids"));
        return $pdf->download("bordereau.pdf");
    }



    public function logout()
    {
        auth()->logout();
        return redirect()->route('home');
    }


    public function inscription()
    {
        return view('front.inscription');
    }


    public function prepareCommande($commande)
    {
        $data_show = [];
        foreach ($commande->contenus as $contenu) {
            $prix = $contenu->produit->getPrice(); // Prix TTC
            $tva = $commande->tva; // Taux de TVA en pourcentage
            $prix_tva = $prix / (1 + ($tva / 100)); // Prix HT
            $quantite = $contenu->quantite;
            $all_prix[] = [
                'nom' => $contenu->produit->nom,
                'quantite' => $quantite,
                'prix_unitaire_normal' => round($prix, 3),
                'prix_unitaire_sans_tva' => round($prix_tva, 3),
                'prix_total_normal' => round($contenu->prix_total * $contenu->quantite, 3),
                'prix_total_sans_tva' => round($prix_tva * $quantite, 3),
            ];
        }
        $total_ht = round(array_sum(array_column($all_prix, 'prix_total_sans_tva')), 3);
        $total_tva = round(array_sum(array_column($all_prix, 'prix_total_sans_tva')) - $total_ht, 3);
        $total_ttc = round($total_ht + $total_tva, 3);
        $data_show = [
            'total_ht' => round($total_ht, 3),
            'total_tva' => round($total_tva, 3),
            'total_ttc' => round($total_ttc + $commande->frais ?? 0 + $commande->timbre ?? 0, 3),
            'all_prix' => $all_prix,
        ];
        return $data_show;
    }









}
