<?php

namespace App\Services;

use App\Models\commandes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class JAxService
{

    protected $token;
    protected $apiUrl;

    public function __construct()
    {
        $this->token = config('app.client_jax_token');
        $this->apiUrl = config('app.jax_url_api');
    }


    public function CreateColis($id_commande)
    {
        $commande = commandes::find($id_commande);
        $dataToSend = [
            "referenceExterne" => "",
            "nomContact" => $commande->nom . " " . $commande->prenom ?? "",
            "tel" => $commande->phone ?? "",
            "tel2" =>  "",
            "adresseLivraison" => $commande->adresse ?? "",
            "governorat" => $commande->id_gouvernorat,
            "delegation" =>  $commande->gouvernorat->nom,
            "description" => $commande->ProduitsText(),
            "cod" => $commande->montant(),
            "echange" => 0
        ];
        $response = Http::withToken($this->token)->post($this->apiUrl . "/user/colis/add", $dataToSend);
        return $response;
    }



    
    public function GetAllGouvernorat()
    {
        $response = Http::withToken($this->token)->get($this->apiUrl . "/gouvernorats");
        return $response;
       
    }


    public function GetStatutColis($id_colis)
    {
        $response = Http::withToken($this->token)->get($this->apiUrl . "/user/colis/getstatubyean/" . $id_colis);
        if ($response->successful()) {
            $data = $response->json();
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Erreur lors de la requête'], $response->status());
        }
    }


    public function GetAllColisStatut()
    {
        $response = Http::withToken($this->token)->get($this->apiUrl . "/statuts");
        if ($response->successful()) {
            $data = $response->json();
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Erreur lors de la requête'], $response->status());
        }
    }


    public function refresh()
    {
        $commandes = commandes::whereNotNull('code_in_api')
        ->where('statut','!=','Livré')
        ->where('etat','confirmé')
        ->get();
        foreach ($commandes as $commande) {
            $data = $this->GetStatutColis($commande->code_in_api);
            $statut = $data->original;
                $commande->statut = $statut;
                $commande->save();
        }
        
        return true;
    }



}
