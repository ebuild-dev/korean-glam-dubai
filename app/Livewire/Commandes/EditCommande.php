<?php

namespace App\Livewire\Commandes;

use App\Http\Traits\ListGouvernorats as TraitsListGouvernorats;
use App\Models\config;
use App\Models\contenu_commande;
use App\Models\gouvernorats;
use Livewire\Component;
use App\Http\Controllers\JaxApi;
use App\Models\commandes;
use App\Models\produits;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EditCommande extends Component
{
    public $rechercheProduit = '', $quantiteAjout = 1;
    public $quantitesModal = [];
    public $quantitesRecherche = [], $limiteProduits = 12;

    public function updatedRechercheProduit()
    {
        $this->limiteProduits = 12;
    }

    public function voirPlusProduits()
    {
        $this->limiteProduits = min((int) $this->limiteProduits + 12, 120);
    }

    #[\Livewire\Attributes\On('preparer-articles')]
    public function preparerArticles()
    {
        $this->resetValidation();
        $this->reset('rechercheProduit', 'quantiteAjout', 'quantitesRecherche', 'limiteProduits');
        $this->quantitesModal = $this->commande->contenus()->pluck('quantite', 'id_produit')->all();
    }

    public function selectionnerArticle($idProduit)
    {
        $produit = produits::findOrFail($idProduit);
        $this->quantitesRecherche[$produit->id] ??= 1;
        $champ = 'quantitesRecherche.' . $produit->id;
        $this->validate([$champ => 'required|integer|min:1']);
        $quantite = (int) ($this->quantitesModal[$produit->id] ?? 0) + (int) $this->quantitesRecherche[$produit->id];
        $existante = (int) $this->commande->contenus()->where('id_produit', $produit->id)->sum('quantite');
        if ($quantite > $existante + $produit->stock) {
            throw ValidationException::withMessages([$champ => 'Stock insuffisant pour cette quantité.']);
        }
        $this->quantitesModal[$produit->id] = $quantite;
        $this->resetValidation($champ);
        $this->quantitesRecherche[$produit->id] = 1;
    }

    public function enregistrerArticles()
    {
        $this->validate([
            'quantitesModal' => 'required|array',
            'quantitesModal.*' => 'required|integer|min:1',
        ]);
        DB::transaction(function () {
            $commande = commandes::lockForUpdate()->findOrFail($this->commande->id);
            abort_unless($commande->modifiable(), 403);
            foreach ($this->quantitesModal as $idProduit => $quantite) {
                $contenu = $commande->contenus()->where('id_produit', $idProduit)->first();
                if ($contenu) {
                    $this->change($contenu->id, $quantite);
                } else {
                    $this->quantiteAjout = $quantite;
                    $this->ajouterArticle($idProduit);
                }
            }
        });
        $this->commande->unsetRelation('contenus');
        $this->reset('quantiteAjout');
        session()->flash('success', 'Les articles et les quantités ont été enregistrés.');
    }

    public function ajouterArticle($idProduit)
    {
        $this->validate(['quantiteAjout' => 'required|integer|min:1']);
        DB::transaction(function () use ($idProduit) {
            $commande = commandes::lockForUpdate()->findOrFail($this->commande->id);
            abort_unless($commande->modifiable(), 403);
            $produit = produits::lockForUpdate()->findOrFail($idProduit);
            $quantite = (int) $this->quantiteAjout;
            if ($produit->stock < $quantite) {
                throw ValidationException::withMessages(['quantiteAjout' => 'La quantité demandée dépasse le stock disponible.']);
            }
            $contenu = $commande->contenus()->where('id_produit', $produit->id)->first();
            if (!$contenu) {
                $contenu = new contenu_commande();
                $contenu->id_commande = $commande->id;
                $contenu->id_produit = $produit->id;
                $contenu->quantite = 0;
                $contenu->prix_unitaire = $produit->getPrice();
            }
            $contenu->quantite += $quantite;
            $contenu->prix_total = $contenu->prix_unitaire * $contenu->quantite;
            $contenu->benefice = ($contenu->prix_unitaire - $produit->prix_achat) * $contenu->quantite;
            $contenu->save();
            $produit->diminuer_stock($quantite);
        });
        $this->commande->unsetRelation('contenus');
        $this->reset('quantiteAjout', 'rechercheProduit');
        session()->flash('success', 'Article ajouté à la commande.');
    }
    public $commande, $gouvernoratsTunisie, $nom, $prenom, $adresse, $id_gouvernorat, $phone, $frais,$timbre,$tva;

    public function mount($commande)
    {
        $this->commande = $commande;
        $this->nom = $commande->nom;
        $this->prenom = $commande->prenom;
        $this->adresse = $commande->adresse;
        $this->id_gouvernorat = $commande->id_gouvernorat
            ?: gouvernorats::where('nom', $commande->city)->value('id');
        $this->phone = $commande->phone;
        $this->timbre = $commande->timbre;
        $this->tva = $commande->tva;
        $this->frais = $commande->frais;

    }
    public function render()
    {
        $this->gouvernoratsTunisie = gouvernorats::whereIn('nom', config('locations.emirates'))->get();
        $config = config::first();
        $recherche = trim($this->rechercheProduit);
        $produitsTrouves = produits::where(function ($query) use ($recherche) {
            $query->where('nom', 'like', '%' . $recherche . '%')
                ->orWhere('reference', 'like', '%' . $recherche . '%');
        })->orderByDesc('stock')->orderBy('nom')->limit(min((int) $this->limiteProduits, 120) + 1)->get();
        $plusDeProduits = $produitsTrouves->count() > $this->limiteProduits && $this->limiteProduits < 120;
        $produitsTrouves = $produitsTrouves->take($this->limiteProduits);
        foreach ($produitsTrouves as $produit) {
            $this->quantitesRecherche[$produit->id] ??= 1;
        }
        return view('livewire.commandes.edit-commande')
            ->with('articlesModal', produits::withTrashed()->whereIn('id', array_keys($this->quantitesModal))->get())
            ->with('produitsTrouves', $produitsTrouves)
            ->with('plusDeProduits', $plusDeProduits)
            ->with('config', $config);
    }
    public function save()
    {
        $this->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'nullable|string|max:100',
            'adresse' => 'required|string|max:150',
            'phone' => 'required|string|max:100',
            'id_gouvernorat' => 'required|integer|exists:gouvernorats,id',
            'frais' => 'nullable',
            'timbre' => 'nullable',
            'tva' => 'nullable',
        ]);
        $config = config::first();
        $this->commande->nom = $this->nom;
        $this->commande->prenom = $this->prenom;
        $this->commande->adresse = $this->adresse;
        $this->commande->phone = $this->phone;
        $this->commande->id_gouvernorat = $this->id_gouvernorat;
        $gouvernorat = gouvernorats::find($this->id_gouvernorat);

        if ($gouvernorat) {
            $this->commande->city = $gouvernorat->nom;  // Met le nom
        } else {
            $this->commande->city = null; // ou une valeur par défaut
        }        $this->commande->frais = $this->frais ? $config->frais : null;
        if($this->frais){
            $this->commande->frais = $config->getFrais();
        }else{
            $this->commande->frais = 0;
        }
        if($this->tva){
            $this->commande->tva = $config->getTva();
        }else{
            $this->commande->tva = 0;
        }
        if($this->timbre){
            $this->commande->timbre = $config->getTimbre();
        }else{
            $this->commande->timbre = 0;
        }
        $this->commande->save();

        //flash success message en frnancais
        session()->flash('success', __('Les informations de la commandes ont été  modifiés !'));
    }
    public function reload(JaxApi $JaxApi){
        $commande = commandes::find($this->commande->id);
        //on retire maintenant le stock
        foreach ($commande->contenus as $contenus) {
            $article = produits::find($contenus->produit->id_produit);
            if ($article) {
                $article->diminuer_stock($contenus->produit->quantite);
            }
        }

        try {
            $response = $JaxApi->CreateAColis($this->commande->id);
            if ($response->successful()) {
                $jax = $response->json();
            } else {
                $jax = null;
            }
        } catch (\Exception $e) {
            $jax = null;
        }
        // Vérifiez que la rép;onse contient bien le code
        $sidCode = isset($jax['code']) ? $jax['code'] : null;

        if ($sidCode) {
            $commande->code_in_api = $sidCode;
        }

        if ($commande) {
            $commande->etat = "confirmé";
            $commande->save();
        }

        return redirect()->route('details_commande',['id' => $commande->id]);
    }
    public function change($id_contenu, $quantite)
    {
        if (filter_var($quantite, FILTER_VALIDATE_INT) === false || $quantite < 1) {
            return;
        }
        DB::transaction(function () use ($id_contenu, $quantite) {
            $commande = commandes::lockForUpdate()->findOrFail($this->commande->id);
            abort_unless($commande->modifiable(), 403);
            $contenu = $commande->contenus()->findOrFail($id_contenu);
            $produit = $contenu->produit()->lockForUpdate()->firstOrFail();
            $difference = (int) $quantite - $contenu->quantite;
            if ($difference > 0 && $difference > $produit->stock) {
                throw ValidationException::withMessages(['quantiteAjout' => 'La quantité demandée dépasse le stock disponible.']);
            }
            if ($difference > 0) {
                $produit->diminuer_stock($difference);
            } elseif ($difference < 0) {
                $produit->retourner_stock(-$difference);
            }
            $contenu->quantite = (int) $quantite;
            $contenu->prix_total = $contenu->prix_unitaire * $contenu->quantite;
            $contenu->benefice = ($contenu->prix_unitaire - $produit->prix_achat) * $contenu->quantite;
            $contenu->save();
        });
        $this->commande->unsetRelation('contenus');
    }
    public function delete($id)
    {
        $contenu = contenu_commande::find(intval($id));
        if (!$contenu) {
            //flash error message
            session()->flash('warning', 'Contenu non trouvé');
            return;
        }
        $contenu->delete();
        //fash mesage
        session()->flash('success', 'Le contenu a été supprimé de votre commande');

        $total = $this->commande->contenus->count();
        if ($total == 0) {
            //supprimer la commande
            $this->commande->delete();
            //redirection vers la page des commandes
            return redirect()->route('commandes')->with('success', '');
        }
    }


}
