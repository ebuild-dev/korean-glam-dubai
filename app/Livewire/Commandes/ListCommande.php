<?php

namespace App\Livewire\Commandes;

use App\Models\commandes;
use App\Models\gouvernorats;
use App\Models\produits;
use App\Services\LightSpeedService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Http;

class ListCommande extends Component
{
    use WithPagination;

    public $selectedCommandes = [];
    public $date, $statut, $key, $gouvernoratsTunisie, $gouvernorat, $statut2;
    public $preorder = '';

    public $statuts_cmd = [];


    public function updatedKey($value)
    {
        $this->key = $value;
        $this->resetPage();
    }

    public function render(LightSpeedService $lightspeed)
    {
        $commandesQuery = commandes::query();
        if (strlen($this->date) > 0) {
            $commandesQuery->whereDate('created_at', $this->date);
        }
        if (strlen($this->gouvernorat) > 0) {
            $gouvernorat = gouvernorats::find($this->gouvernorat);
            if ($gouvernorat) {
                $commandesQuery->where('city', $gouvernorat->nom);
            }
        }
        if (strlen($this->statut) > 0) {
            if ($this->statut == "retournée") {
                $commandesQuery->where('statut', $this->statut)
                    ->where('etat', "confirmé");
            } else {
                $commandesQuery->where('statut', $this->statut);

            }
        }
        if (strlen($this->statut2) > 0) {
            if ($this->statut2 == "confirmer") {
                $commandesQuery->where('etat', "confirmé");
            } else {
                $commandesQuery->where('etat', "annulé");
            }
        }
        if (strlen($this->key) > 0) {
            $commandesQuery->where('nom', 'like', '%' . $this->key . '%')
                ->orWhere('adresse', 'like', '%' . $this->key . '%')
                ->orWhere('phone', 'like', '%' . $this->key . '%')
                ->orWhere('prenom', 'like', '%' . $this->key . '%');
        }
        if ($this->preorder !== '') {
            if ($this->preorder == 1) {
                // Commandes qui contiennent AU MOINS un produit avec pris_order = true
                $commandesQuery->whereHas('contenus.produit', function ($q) {
                    $q->where('pris_order', true);
                });
            } else {
                // Commandes qui ne contiennent AUCUN produit pris_order = true
                $commandesQuery->whereDoesntHave('contenus.produit', function ($q) {
                    $q->where('pris_order', true);
                });
            }
        }

        $commandes = $commandesQuery
            ->with(['contenus.produit', 'Auteur', 'gouvernorat'])
            ->Orderby('id', "Desc")
            ->paginate(80);
        $total = commandes::count();
        $this->gouvernoratsTunisie = gouvernorats::whereIn('nom', config('locations.emirates'))->get();
        // 👉 On reset le tableau des statuts
        $this->statuts_cmd = [];

        // 👉 Auth une seule fois
        $hasTrackedOrders = $commandes->contains(fn ($commande) => !empty($commande->code_in_api));
        $token = $hasTrackedOrders ? $lightspeed->authenticate() : false;
        foreach ($commandes as $commande) {

            if (!$commande->code_in_api) {
                $this->statuts_cmd[$commande->id] = "—";
                continue;
            }

            if (!$token) {
                $this->statuts_cmd[$commande->id] = "Suivi temporairement indisponible";
                continue;
            }

            $cacheKey = 'lightspeed.order-status.' . hash('sha256', $commande->code_in_api);
            $trackingStatus = Cache::remember($cacheKey, now()->addSeconds(20), function () use ($lightspeed, $token, $commande) {
                $response = $lightspeed->getStatusCmd($token, $commande->code_in_api);
                if (!$response || !$response->successful()) {
                    return '__unavailable__';
                }

                return $response->json()['Value']['Status'] ?? 'Inconnu';
            });

            if ($trackingStatus === '__unavailable__') {
                $this->statuts_cmd[$commande->id] = "Suivi temporairement indisponible";
                continue;
            }

            $this->statuts_cmd[$commande->id] = $trackingStatus;

            // Le suivi transporteur reste informatif pour préserver le statut manuel.
        }

        return view('livewire.commandes.list-commande', compact("commandes", "total"));
    }

    public function updateEtat($commandeId, $newEtat)
    {
        abort_unless(auth()->user()?->can('order_edit'), 403);
        validator(['etat' => $newEtat], ['etat' => 'required|in:attente,confirmé,annulé'])->validate();
        $commande = commandes::findOrFail($commandeId);
        if ($commande->etat === 'annulé' && $newEtat !== 'annulé') {
            $commande->statut = 'créé';
        }
        $commande->etat = $newEtat;
        $commande->save();
        session()->flash('success', 'État mis à jour avec succès !');
    }

    public function updateStatus($commandeId, $newStatus)
    {
        return $this->updateStatut($commandeId, $newStatus);
    }
    // ⚡ Méthode pour mettre à jour le statut
    public function updateStatut($commandeId, $newStatut)
    {
        abort_unless(auth()->user()?->can('order_edit'), 403);
        validator(['statut' => $newStatut], [
            'statut' => 'required|in:créé,traitement,livraison,livré,payée,planification_retour,retournée',
        ])->validate();
        $commande = commandes::find($commandeId);

        if (!$commande) {
            session()->flash('error', 'Commande introuvable.');
            return;
        }

        if ($commande->statut === $newStatut) {
            return;
        }
        if ($commande) {
            $commande->statut = $newStatut;

            //retourner le stock si l'etat de dla command epasser a retourner
            if ($newStatut == "retournée") {
                foreach ($commande->contenus as $contenus) {
                    $article = produits::find($contenus->id_produit);
                    if ($article) {
                        $article->retourner_stock($contenus->quantite);
                    }
                }
                // 🔻 Si la commande avait donné des points, les retirer
                if ($commande->is_point && $commande->Auteur) {
                    $total_points_achat = 0;
                    $total_points_vente = 0;
                    foreach ($commande->contenus as $contenus) {
                        $produit = produits::find($contenus->id_produit);
                        if ($produit && isset($produit->points_vente)) {
                            $total_points_achat += ($produit->points_achat * $contenus->quantite);
                            $total_points_vente += ($produit->points_vente * $contenus->quantite);

                        }
                    }
                    Log::info("total points achat ".$total_points_achat);

                    $commande->Auteur->points = max(0, $commande->Auteur->points + $total_points_achat - $total_points_vente);
                    $commande->Auteur->save();
                }
            }
            if ($newStatut == "livré") {
                if ($commande->Auteur) { // Vérifie que la commande a un auteur
                    $total_points = 0;

                    foreach ($commande->contenus as $contenus) {
                        // Récupérer le produit associé
                        $produit = produits::find($contenus->id_produit);

                        if ($produit && isset($produit->points_vente)) {
                            // Multiplier les points par la quantité commandée
                            $total_points += ($produit->points_vente * $contenus->quantite);
                        }
                    }

                    // Ajouter les points à l'auteur
                    $commande->Auteur->points += $total_points;
                    $commande->Auteur->save();
                }
            }
            //enregistrer le chagement de l'etat de la commande
            $commande->save();
        }

        session()->flash('success', '✅ Statut mis à jour avec succès !');
    }

    public function delete($id)
    {
        $commande = commandes::find($id);
        if ($commande) {
            $commande->delete();

            //flash message
            session()->flash('success', 'Commande supprimée avec succès');
        }
        return view('livewire.commandes.list-commande');
    }

    public function filtrer()
    {
        //reset page
        $this->resetPage();
    }


    public function confirmer($id, LightSpeedService $lightspeed)
    {
        $commande = commandes::find($id);
        if (!$commande) {
            session()->flash('error', 'Commande introuvable.');
            return;
        }

        if ($commande->etat === "confirmé") {
            session()->flash('info', 'Cette commande est déjà confirmée.');
            return;
        }

        $token = $lightspeed->authenticate();
        $deliveryRegistered = false;

        try {

            // 👉 1. Vérification token LightSpeed
            if (!$token) {
                Log::error("LightSpeed Auth Failed");
                throw new \Exception("Authentication failed");
            }

            // 👉 2. Préparation du payload EXACT (LightSpeed n'accepte rien d'autre)
            $lightSpeedOrder = [
                "Client" => $commande->nom,
                "Adresse_client" => $commande->adresse,
                "mail_client" => $commande->email,
                "ville_client" => $commande->city,
                "TEL1_client" => $commande->phone,
                "TEL2_client" => "",
                "gouvernorat_client" => $commande->city,
                "designation" => "Commande #" . $commande->id,
                "nbr_article" => $commande->contenus()->sum('quantite'),
                "montant" => $commande->montant(),
                "echange" => 0,
                "ID_CMD_Echange" => "",
                "description_echange" => "",
               // "ETAT" => 0,
               // "motif_etat" => "Brouillon"
            ];

            // 👉 Format EXACT exigé
            $payload = [
                [
                    $lightSpeedOrder
                ]
            ];

         //   Log::info("Payload envoyé à LightSpeed", $payload);

            // 👉 3. Appel API LightSpeed
            $response = $lightspeed->addOrder($token, $payload);

            if (!$response || !$response->successful()) {
                Log::error("Erreur LightSpeed", ['response' => $response?->json()]);
                throw new \Exception("Invalid LightSpeed response");
            }

            // 👉 4. Réponse JSON LightSpeed
            $lsData = $response->json();

            Log::info("LightSpeed Response", $lsData);

            $lsCode = $lsData["BarCodes"][0] ?? null;

            if ($lsCode) {
                $commande->code_in_api = $lsCode;
                $deliveryRegistered = true;
            } else {
                Log::warning("LightSpeed: Aucun code reçu", $lsData);
                throw new \Exception('Missing LightSpeed barcode');
            }
        }
        catch (\Exception $e) {

            Log::warning("LightSpeed delivery registration failed; confirming order locally", [
                "error" => $e->getMessage()
            ]);
        }
        //on retirer maintenant le stock
        $commande->load('contenus.produit');
        foreach ($commande->contenus as $contenus) {

            $article = $contenus->produit;
            if ($article) {

                $article->diminuer_stock($contenus->quantite);
            }
        }
        $commande->etat = "confirmé";
        $commande->save();

        if ($deliveryRegistered) {
            session()->flash('success', 'Commande confirmée et envoyée au service de livraison.');
        } else {
            session()->flash('warning', 'Commande confirmée. Le service de livraison est indisponible : le colis devra être créé dès son rétablissement.');
        }
    }
    public function annuler($id)
    {
        $commande = commandes::find($id);
        if ($commande) {
            $commande->statut = "retournée";
            $commande->etat = "annulé";
            $commande->save();
        }
    }
    public function toggleCommandeSelection($commandeId)
    {
        if (in_array($commandeId, $this->selectedCommandes)) {
            $this->selectedCommandes = array_diff($this->selectedCommandes, [$commandeId]);
        } else {
            $this->selectedCommandes[] = $commandeId;
        }
    }
    public function getSelectedCommandes()
    {
        //check if $this->selectedCommandes is not empty
        if (count($this->selectedCommandes) > 0) {
            $ids = json_encode($this->selectedCommandes);
            return redirect()->route('print_bordereau', ["ids" => $ids]);
        } else {
            return false;
        }
    }
    public function suiviLightSpeed($id, LightSpeedService $lightspeed)
    {
        $commande = commandes::find($id);

        if (!$commande || !$commande->code_in_api) {
            $this->statuts_cmd[$id] = "Aucun numéro de suivi trouvé.";
            return;
        }

        // Authentification
        $token = $lightspeed->authenticate();
        if (!$token) {
            $this->statuts_cmd[$id] = "Suivi temporairement indisponible";
            return;
        }

        // Appel API
        $response = $lightspeed->getStatusCmd($token, $commande->code_in_api);

        if (!$response || !$response->successful()) {
            $this->statuts_cmd[$id] = "Suivi temporairement indisponible";
            return;
        }

        $tracking = $response->json()["Value"] ?? null;

        if (!$tracking) {
            $this->statuts_cmd[$id] = "Aucune information trouvée.";
            return;
        }

        // Statut exact
        $status = $tracking["Status"] ?? "Inconnu";

        // 🔥 On sauvegarde le statut dans un tableau par ID
        $this->statuts_cmd[$id] = $status;
    }

}
