<?php

namespace App\Livewire\Promotions;

use App\Models\Brand;
use App\Models\categories;
use App\Models\produits;
use App\Models\Souscategorie;
use App\Models\SousSousCategorie;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\promotions as ModelPromotion;

class Promotions extends Component
{
    /*
    |--------------------------------------------------------------------------
    | Promotion
    |--------------------------------------------------------------------------
    */

    public $titre;
    public $pourcentage;
    public $debut;
    public $fin;


    /*
    |--------------------------------------------------------------------------
    | Sélection
    |--------------------------------------------------------------------------
    */

    public $all = false;

    // Produit passé depuis une autre page
    public $produit = null;


    /*
    |--------------------------------------------------------------------------
    | Type de sélection
    |--------------------------------------------------------------------------
    |
    | categorie
    | sous_categorie
    | sous_sous_categorie
    | marque
    | gamme
    | produits
    | tous
    |
    */

    public $selection_type = 'categorie';


    /*
    |--------------------------------------------------------------------------
    | Filtres
    |--------------------------------------------------------------------------
    */

    public $categorie_selected = null;

    public $sous_categorie_selected = null;

    public $sous_sous_categorie_selected = null;

    public $brand_selected = null;

    public $gamme_selected = null;


    /*
    |--------------------------------------------------------------------------
    | Produits sélectionnés
    |--------------------------------------------------------------------------
    |
    | C'est cette variable qui détermine réellement
    | quels produits recevront la promotion.
    |
    */

    public $produits_selected = [];


    /*
    |--------------------------------------------------------------------------
    | Recherche produits
    |--------------------------------------------------------------------------
    */

    public $search_produit = '';


    /*
    |--------------------------------------------------------------------------
    | Mount
    |--------------------------------------------------------------------------
    */

    public function mount($produit = null)
    {
        $this->produit = $produit;

        /*
        |--------------------------------------------------------------------------
        | Si un produit est passé directement
        |--------------------------------------------------------------------------
        */

        if ($this->produit) {

            $this->selection_type = 'produits';

            $this->produits_selected = [
                (string) $this->produit->id
            ];
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Changement du type de sélection
    |--------------------------------------------------------------------------
    */

    public function updatedSelectionType($value)
    {
        $this->all = false;

        $this->produit = null;

        $this->categorie_selected = null;

        $this->sous_categorie_selected = null;

        $this->sous_sous_categorie_selected = null;

        $this->brand_selected = null;

        $this->gamme_selected = null;

        $this->produits_selected = [];

        $this->search_produit = '';
    }


    /*
    |--------------------------------------------------------------------------
    | Catégorie
    |--------------------------------------------------------------------------
    */

    public function updatedCategorieSelected($value)
    {
        $this->all = false;

        $this->produit = null;

        $this->sous_categorie_selected = null;

        $this->sous_sous_categorie_selected = null;

        if (!$value) {

            $this->produits_selected = [];

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Charger tous les produits de la catégorie
        |--------------------------------------------------------------------------
        |
        | Au départ ils sont tous cochés.
        | L'utilisateur peut ensuite décocher ceux qu'il ne veut pas.
        |
        */

        $this->produits_selected = produits::where(
            'id_categorie',
            $value
        )
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->toArray();
    }


    /*
    |--------------------------------------------------------------------------
    | Sous-catégorie
    |--------------------------------------------------------------------------
    */

    public function updatedSousCategorieSelected($value)
    {
        $this->all = false;

        $this->produit = null;

        $this->sous_categorie_selected = $value ?: null;

        $this->sous_sous_categorie_selected = null;


        if (!$value) {

            $this->produits_selected = [];

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Récupérer la catégorie parente
        |--------------------------------------------------------------------------
        */

        $sousCategorie = Souscategorie::find($value);

        if ($sousCategorie) {

            $this->categorie_selected =
                $sousCategorie->id_categorie;
        }


        /*
        |--------------------------------------------------------------------------
        | Sélectionner initialement tous les produits
        |--------------------------------------------------------------------------
        */

        $this->produits_selected = produits::where(
            'id_sous_categorie',
            $value
        )
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->toArray();
    }


    /*
    |--------------------------------------------------------------------------
    | Sous-sous-catégorie
    |--------------------------------------------------------------------------
    */

    public function updatedSousSousCategorieSelected($value)
    {
        $this->all = false;

        $this->produit = null;

        $this->sous_sous_categorie_selected =
            $value ?: null;


        if (!$value) {

            $this->produits_selected = [];

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Récupérer la sous-sous-catégorie
        |--------------------------------------------------------------------------
        */

        $sousSousCategorie =
            SousSousCategorie::find($value);


        if ($sousSousCategorie) {

            $this->categorie_selected =
                $sousSousCategorie->id_categorie;

            $this->sous_categorie_selected =
                $sousSousCategorie->id_souscategorie;
        }


        /*
        |--------------------------------------------------------------------------
        | Produits de la sous-sous-catégorie
        |--------------------------------------------------------------------------
        */

        $this->produits_selected = produits::whereHas(
            'sousSousCategories',
            function ($query) use ($value) {

                $query->where(
                    'sous_souscategorie_id',
                    $value
                );
            }
        )
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->toArray();
    }


    /*
    |--------------------------------------------------------------------------
    | Marque
    |--------------------------------------------------------------------------
    */

    public function updatedBrandSelected($value)
    {
        $this->all = false;

        $this->produit = null;

        if (!$value) {

            $this->produits_selected = [];

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Produits de la marque
        |--------------------------------------------------------------------------
        */

        $this->produits_selected = produits::where(
            'id_brand',
            $value
        )
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->toArray();
    }


    /*
    |--------------------------------------------------------------------------
    | Gamme
    |--------------------------------------------------------------------------
    */

    public function updatedGammeSelected($value)
    {
        $this->all = false;

        $this->produit = null;

        if (!$value) {

            $this->produits_selected = [];

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Produits de la gamme
        |--------------------------------------------------------------------------
        */

        $this->produits_selected = produits::where(
            'gamme_id',
            $value
        )
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->toArray();
    }


    /*
    |--------------------------------------------------------------------------
    | Tous les produits
    |--------------------------------------------------------------------------
    */

    public function updatedAll($value)
    {
        if (!$value) {

            $this->produits_selected = [];

            return;
        }


        $this->produit = null;

        $this->selection_type = 'tous';

        $this->categorie_selected = null;

        $this->sous_categorie_selected = null;

        $this->sous_sous_categorie_selected = null;

        $this->brand_selected = null;

        $this->gamme_selected = null;


        /*
        |--------------------------------------------------------------------------
        | Sélectionner tous les produits
        |--------------------------------------------------------------------------
        */

        $this->produits_selected = produits::pluck('id')
            ->map(fn ($id) => (string) $id)
            ->toArray();
    }


    /*
    |--------------------------------------------------------------------------
    | Sélectionner tous les produits affichés
    |--------------------------------------------------------------------------
    */

    public function selectAllProducts()
    {
        /*
        |--------------------------------------------------------------------------
        | Tous les produits
        |--------------------------------------------------------------------------
        */

        if ($this->selection_type === 'tous') {

            $this->produits_selected = produits::pluck('id')
                ->map(fn ($id) => (string) $id)
                ->toArray();

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Construire la requête correspondant au filtre actuel
        |--------------------------------------------------------------------------
        */

        $query = $this->getProductsQuery();


        /*
        |--------------------------------------------------------------------------
        | Ajouter les produits disponibles à la sélection
        |--------------------------------------------------------------------------
        */

        $ids = $query->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | On remplace la sélection par les produits affichés
        |--------------------------------------------------------------------------
        */

        $this->produits_selected = $ids;
    }


    /*
    |--------------------------------------------------------------------------
    | Désélectionner tous les produits affichés
    |--------------------------------------------------------------------------
    */

    public function unselectAllProducts()
    {
        /*
        |--------------------------------------------------------------------------
        | Ici on désélectionne réellement tous les produits
        |--------------------------------------------------------------------------
        */

        $this->produits_selected = [];
    }


    /*
    |--------------------------------------------------------------------------
    | Requête des produits disponibles
    |--------------------------------------------------------------------------
    */

    private function getProductsQuery()
    {
        $query = produits::query();


        /*
        |--------------------------------------------------------------------------
        | Catégorie
        |--------------------------------------------------------------------------
        */

        if (
            $this->selection_type === 'categorie'
            && $this->categorie_selected
        ) {

            $query->where(
                'id_categorie',
                $this->categorie_selected
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Sous-catégorie
        |--------------------------------------------------------------------------
        */

        elseif (
            $this->selection_type === 'sous_categorie'
            && $this->sous_categorie_selected
        ) {

            $query->where(
                'id_sous_categorie',
                $this->sous_categorie_selected
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Sous-sous-catégorie
        |--------------------------------------------------------------------------
        */

        elseif (
            $this->selection_type === 'sous_sous_categorie'
            && $this->sous_sous_categorie_selected
        ) {

            $query->whereHas(
                'sousSousCategories',
                function ($q) {

                    $q->where(
                        'sous_souscategorie_id',
                        $this->sous_sous_categorie_selected
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Marque
        |--------------------------------------------------------------------------
        */

        elseif (
            $this->selection_type === 'marque'
            && $this->brand_selected
        ) {

            $query->where(
                'id_brand',
                $this->brand_selected
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Gamme
        |--------------------------------------------------------------------------
        */

        elseif (
            $this->selection_type === 'gamme'
            && $this->gamme_selected
        ) {

            $query->where(
                'gamme_id',
                $this->gamme_selected
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Quelques produits
        |--------------------------------------------------------------------------
        */

        elseif (
            $this->selection_type === 'produits'
        ) {

            /*
            |--------------------------------------------------------------------------
            | Recherche
            |--------------------------------------------------------------------------
            */

            if ($this->search_produit) {

                $search = trim($this->search_produit);

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'nom',
                        'like',
                        '%' . $search . '%'
                    )

                        ->orWhere(
                            'reference',
                            'like',
                            '%' . $search . '%'
                        );
                });
            }
        }


        return $query
            ->orderBy('nom');
    }


    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        /*
        |--------------------------------------------------------------------------
        | Promotions
        |--------------------------------------------------------------------------
        */

        $promotions = ModelPromotion::orderBy(
            'id',
            'desc'
        )->get();


        /*
        |--------------------------------------------------------------------------
        | Catégories
        |--------------------------------------------------------------------------
        */

        $categories = categories::orderBy(
            'nom'
        )->get();


        /*
        |--------------------------------------------------------------------------
        | Gammes
        |--------------------------------------------------------------------------
        */

        $gammes = \App\Models\Gamme::orderBy(
            'order_column'
        )->get();


        /*
        |--------------------------------------------------------------------------
        | Marques
        |--------------------------------------------------------------------------
        */

        $brands = Brand::orderBy(
            'brandname'
        )->get();


        /*
        |--------------------------------------------------------------------------
        | Sous-catégories
        |--------------------------------------------------------------------------
        */

        $sousCategories = collect();

        if ($this->categorie_selected) {

            $sousCategories = Souscategorie::where(
                'id_categorie',
                $this->categorie_selected
            )
                ->orderBy('nom')
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | Sous-sous-catégories
        |--------------------------------------------------------------------------
        */

        $sousSousCategories = collect();

        if ($this->sous_categorie_selected) {

            $sousSousCategories =
                SousSousCategorie::where(
                    'id_souscategorie',
                    $this->sous_categorie_selected
                )
                    ->orderBy('order_column')
                    ->orderBy('nom')
                    ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | Produits disponibles
        |--------------------------------------------------------------------------
        */

        $produitsDisponibles = collect();


        /*
        |--------------------------------------------------------------------------
        | Ne pas afficher la liste pour "tous"
        |--------------------------------------------------------------------------
        */

        if ($this->selection_type !== 'tous') {

            /*
            |--------------------------------------------------------------------------
            | Si un filtre est sélectionné
            |--------------------------------------------------------------------------
            */

            if (
                ($this->selection_type === 'categorie'
                    && $this->categorie_selected)

                ||

                ($this->selection_type === 'sous_categorie'
                    && $this->sous_categorie_selected)

                ||

                ($this->selection_type === 'sous_sous_categorie'
                    && $this->sous_sous_categorie_selected)

                ||

                ($this->selection_type === 'marque'
                    && $this->brand_selected)

                ||

                ($this->selection_type === 'gamme'
                    && $this->gamme_selected)

                ||

                (
                    $this->selection_type === 'produits'
                    && $this->search_produit
                )
            ) {

                $produitsDisponibles =
                    $this->getProductsQuery()->get();
            }
        }

        $produitsRecherche = collect();

        if (
            $this->search_produit &&
            !$this->produit
        ) {
            $search = trim($this->search_produit);

            $produitsRecherche = produits::with('marque')
                ->where(function ($query) use ($search) {

                    $query->where(
                        'nom',
                        'like',
                        '%' . $search . '%'
                    );

                    $query->orWhere(
                        'reference',
                        'like',
                        '%' . $search . '%'
                    );
                })
                ->orderBy('nom')
                ->limit(100)
                ->get();
        }
        return view(
            'livewire.promotions.promotions',
            compact(
                'promotions',
                'categories',
                'gammes',
                'brands',
                'sousCategories',
                'sousSousCategories',
                'produitsDisponibles',
                'produitsRecherche'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Création de la promotion
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $this->validate([
            'titre' => 'required|string|max:255',

            'pourcentage' =>
                'required|integer|between:2,100',

            'debut' =>
                'required|date',

            'fin' =>
                'required|date|after:debut',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Vérifier qu'il y a au moins un produit
        |--------------------------------------------------------------------------
        */

        if (
            !$this->all
            && !$this->produit
            && empty($this->produits_selected)
        ) {

            $this->addError(
                'selection',
                'Veuillez sélectionner au moins un produit.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Normaliser les IDs
        |--------------------------------------------------------------------------
        */

        $produitsIds = collect(
            $this->produits_selected
        )
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Produit passé directement
        |--------------------------------------------------------------------------
        */

        if ($this->produit) {

            $produitsIds = collect([
                (int) $this->produit->id
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Tous les produits
        |--------------------------------------------------------------------------
        */

        if ($this->all || $this->selection_type === 'tous') {

            $produitsIds = produits::pluck('id')
                ->map(fn ($id) => (int) $id);
        }


        /*
        |--------------------------------------------------------------------------
        | Sécurité : vérifier les produits existants
        |--------------------------------------------------------------------------
        */

        $produitsIds = produits::whereIn(
            'id',
            $produitsIds
        )
            ->pluck('id');


        if ($produitsIds->isEmpty()) {

            $this->addError(
                'selection',
                'Aucun produit sélectionné.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Création dans une transaction
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($produitsIds) {

            /*
            |--------------------------------------------------------------------------
            | Promotion
            |--------------------------------------------------------------------------
            */

            $promotion = new ModelPromotion();

            $promotion->titre =
                $this->titre;

            $promotion->pourcentage =
                $this->pourcentage;

            $promotion->debut =
                $this->debut;

            $promotion->fin =
                $this->fin;


            /*
            |--------------------------------------------------------------------------
            | Statut
            |--------------------------------------------------------------------------
            */

            $today = Carbon::today();

            $debut = Carbon::parse(
                $this->debut
            )->startOfDay();

            $fin = Carbon::parse(
                $this->fin
            )->endOfDay();


            if ($today->lt($debut)) {

                $promotion->statut =
                    'Programmer';

            } elseif ($today->between(
                $debut,
                $fin
            )) {

                $promotion->statut =
                    'En cours';

            } else {

                $promotion->statut =
                    'Terminer';
            }


            /*
            |--------------------------------------------------------------------------
            | Sauvegarder
            |--------------------------------------------------------------------------
            */

            $promotion->save();


            /*
            |--------------------------------------------------------------------------
            | Appliquer UNIQUEMENT aux produits sélectionnés
            |--------------------------------------------------------------------------
            */

            produits::whereIn(
                'id',
                $produitsIds
            )->update([
                'id_promotion' => $promotion->id
            ]);
        });


        /*
        |--------------------------------------------------------------------------
        | Message
        |--------------------------------------------------------------------------
        */

        session()->flash(
            'success',
            'Promotion ajoutée avec succès.'
        );


        /*
        |--------------------------------------------------------------------------
        | Reset
        |--------------------------------------------------------------------------
        */

        $this->resetForm();
    }


    /*
    |--------------------------------------------------------------------------
    | Reset formulaire
    |--------------------------------------------------------------------------
    */

    private function resetForm()
    {
        $this->titre = null;

        $this->pourcentage = null;

        $this->debut = null;

        $this->fin = null;

        $this->all = false;

        $this->produit = null;

        $this->categorie_selected = null;

        $this->sous_categorie_selected = null;

        $this->sous_sous_categorie_selected = null;

        $this->brand_selected = null;

        $this->gamme_selected = null;

        $this->produits_selected = [];

        $this->search_produit = '';

        $this->selection_type = 'categorie';
    }


    /*
    |--------------------------------------------------------------------------
    | Suppression promotion
    |--------------------------------------------------------------------------
    */

    public function delete($id)
    {
        $promotion =
            ModelPromotion::find($id);


        if (!$promotion) {

            session()->flash(
                'error',
                'Promotion introuvable.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Retirer la promotion des produits
        |--------------------------------------------------------------------------
        */

        produits::where(
            'id_promotion',
            $promotion->id
        )->update([
            'id_promotion' => null
        ]);


        /*
        |--------------------------------------------------------------------------
        | Supprimer la promotion
        |--------------------------------------------------------------------------
        */

        $promotion->delete();


        session()->flash(
            'success',
            'Promotion supprimée avec succès.'
        );
    }
}
