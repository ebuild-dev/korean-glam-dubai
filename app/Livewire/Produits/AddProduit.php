<?php

namespace App\Livewire\Produits;

use App\Models\autres_prix;
use App\Models\Brand;
use App\Models\categories;
use App\Models\Gamme;
use App\Models\produits;
use App\Models\Souscategorie;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SousSousCategorie;
use Livewire\Attributes\On;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;
class AddProduit extends Component
{
    use WithFileUploads;
    #[\Livewire\Attributes\Locked]
    public array $removedGalleryPhotos = [];
    public $stock = null;

    public $nom, $prix, $photo, $photos, $prix_achat, $photo2, $photos2, $produit, $reference, $description;
    public $id_categorie, $id_brand, $id_sous_categorie, $id_sous_sous_categorie; // uniforme avec underscores
    public $new_prix, $new_qte, $best_sellers;
    public $masquer_produit,$pris_order ;

    public $confirmingDeleteAll = false;
// 🔥 remplacer prix / prix_achat par :
    public $points_achat, $points_vente;
    public $id_gamme;
    public $gammes;
    public $sous_souscategories_ids = [];
    public $trend = false;

    public $preventRender = false;
    public $page = 1;

    protected $listeners = [
        'updateSousSousCategories' => 'setSousSousCategories',
        'refreshSousSousCats' => 'loadSousSousCategoriesEvent',
        'searchSousSousCategories' => 'searchSousSousCategories',

    ];
    public function mount($produit, $page = 1)
    {
        if ($produit) {
            $this->produit = $produit->load('sousSousCategories');
            $this->nom = $produit->nom;
            $this->reference = $produit->reference;
            $this->prix = $produit->prix;
            $this->points_achat = $produit->points_achat;
            $this->points_vente = $produit->points_vente;
            $this->photo2 = $produit->FirstImage();
            $this->photos2 = $produit->photos;
            $this->description = $produit->description;
            $this->id_categorie = $produit->id_categorie;
            $this->id_brand = $produit->id_brand;
            $this->best_sellers = $produit->best_sellers;
            $this->masquer_produit = $produit->masquer_produit;
            $this->pris_order=$produit->pris_order;
            $this->id_sous_categorie = $produit->id_sous_categorie;
            $this->id_gamme = $produit->id_gamme ?? $produit->gamme_id; // ✅ ici
            $this->stock = $produit->stock;
            $this->trend = (bool) $produit->trend;
            $this->sous_souscategories_ids = $this->produit->sousSousCategories->pluck('id')->toArray();
            $this->loadSousSousCategoriesEvent();
            // 👉 Préparer les données pour Tagify
            $sousSousCats = $produit->sousSousCategories->map(function ($cat) {
                return ['id' => $cat->id, 'value' => $cat->nom];
            })->toArray();
            $this->page = $page;
            // 🔥 Envoyer directement à Tagify au chargement
            $this->dispatch('loadSousSousCategories', sousSousCats: $sousSousCats);


        }
    }
    public function removeExistingPhoto(int $index): void
    {
        if (!$this->produit) return;

        $photos = json_decode($this->photos2 ?: '[]', true) ?: [];
        $current = json_decode($this->produit->fresh()->photos ?: '[]', true) ?: [];
        if (!array_key_exists($index, $photos) || !in_array($photos[$index], $current, true)) return;

        $this->removedGalleryPhotos = array_values(array_unique([
            ...$this->removedGalleryPhotos, $photos[$index],
        ]));
    }

    public function removeNewPhoto(int $index): void
    {
        if (!is_array($this->photos) || !array_key_exists($index, $this->photos)) return;

        unset($this->photos[$index]);
        $this->photos = array_values($this->photos);
        $this->resetValidation();
    }

    protected function saveGallery(): void
    {
        $existing = json_decode($this->produit->fresh()->photos ?: '[]', true) ?: [];
        $photos = array_values(array_diff($existing, $this->removedGalleryPhotos));
        foreach ($this->photos ?: [] as $photo) {
            $photos[] = $this->saveAsWebp($photo);
        }

        $this->produit->photos = $photos ? json_encode($photos) : null;
        $this->produit->save();
    }

    private function saveAsWebp($file, $folder = 'produits')
    {
        $manager = new ImageManager(new Driver());

        $filename = Str::uuid() . '.webp';
        $path = $folder . '/' . $filename;

        $image = $manager->read($file->getRealPath());

        Storage::disk('public')->put(
            $path,
            (string) $image->toWebp(100)
        );

        return $path;
    }
    #[On('refreshSousSousCats')]
    // Empêche un re-render après certains events
    public function loadSousSousCategoriesEvent()
    {
        $sousSousCats = SousSousCategorie::whereIn('id', $this->sous_souscategories_ids)
            ->get(['id', 'nom'])
            ->map(fn($cat) => ['id' => $cat->id, 'value' => $cat->nom])
            ->toArray();



        // empêche le composant de se re-render
        $this->preventRender = true;

        $this->dispatch('loadSousSousCategories', sousSousCats: $sousSousCats);
    }

    public function setSousSousCategories($data)
    {
        // quand on modifie les tags depuis le front
        if (is_string($data)) $data = json_decode($data, true);
        if (!is_array($data)) return;

        $this->sous_souscategories_ids = collect($data)->pluck('id')->toArray();
    }

    public function render()
    {
        return view('livewire.produits.add-produit', [
            'categories' => Categories::all(),
            'marques' => Brand::all(),
            'sous_categories' => Souscategorie::all(),
            'sous_sous_categories' => SousSousCategorie::all(),
            $this->gammes = \App\Models\Gamme::orderBy('order_column', 'asc')->get(),

        ]);

    }

    #[On('updateSousSousCategories')]
    #[On('updateSousSousCategories')]
    public function updateSousSousCategories($data)
    {
        // Si c’est un tableau de chaînes ou d’IDs simples
        if (is_array($data) && is_numeric(reset($data))) {
            $this->sous_souscategories_ids = array_unique($data);
        }

        // Si c’est une chaîne JSON
        elseif (is_string($data)) {
            $decoded = json_decode($data, true);
            if (is_array($decoded)) {
                // soit c’est une liste d’IDs simples, soit une liste d’objets
                $this->sous_souscategories_ids = collect($decoded)
                    ->map(fn($item) => is_array($item) ? ($item['id'] ?? null) : $item)
                    ->filter()
                    ->unique()
                    ->values()
                    ->toArray();
            }
        }

    }



    /**
     * 🔍 Recherche dynamique de sous-sous-catégories
     */
    #[On('searchSousSousCategories')]
    public function searchSousSousCategories($query = null)
    {
        $search = is_array($query)
            ? ($query['query'] ?? '')
            : ($query ?? '');

        if (!$search) {
            \Log::info('❌ Aucun terme reçu', ['payload' => $query]);
            return;
        }


        $results = \App\Models\SousSousCategorie::where('nom', 'like', "%{$search}%")
            ->orderBy('order_column')
            ->limit(10)
            ->get(['nom as value', 'id']) // 👈 le nom devient le texte du tag
            ->toArray();


        $this->dispatch('updateTagifySuggestions', results: $results);
    }


    //validation
    public function create()
    {
        $this->validate([
            'nom' => 'required|string',
            'reference' => 'required|string|unique:produits,reference',
            'points_achat' => 'required|numeric',
            'points_vente' => 'required|numeric',
            'prix' => 'required|numeric',
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:51200',
            'photos' => 'nullable|array',
            'photos.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:51200',
            'id_categorie' => 'required|exists:categories,id',
            'id_sous_categorie' => 'required|exists:souscategories,id', // ✅ correction ici
            'id_brand' => 'required|exists:brands,id',
            'id_gamme' => 'nullable|exists:gammes,id',
            'sous_souscategories_ids.*' => 'exists:sous_souscategories,id',
            'stock' => 'nullable|numeric|min:0',
            'trend' => 'nullable|boolean',


        ]);
        $stockValue = $this->stock ?: 0;
        $photos = [];

        if ($this->photos) {
            foreach ($this->photos as $photo) {
                $photos[] = $this->saveAsWebp($photo);
            }
        }
        $produit = Produits::create([
            'nom' => $this->nom,
            'reference' => $this->reference,
            'points_achat' => $this->points_achat,
            'points_vente' => $this->points_vente,
            'prix' => $this->prix,
            'description' => $this->description,
            'id_categorie' => $this->id_categorie,
            'id_brand' => $this->id_brand,
            'best_sellers' => $this->best_sellers ? true : false,
            'masquer_produit' => $this->masquer_produit ? true : false,
            'pris_order'=>$this->pris_order? true : false,
            'id_sous_categorie' => $this->id_sous_categorie,
            'gamme_id' => $this->id_gamme,
            'photo' => $this->saveAsWebp($this->photo),
            'stock' => $stockValue, // ✅ si vide, met 0
            'trend' => $this->trend ? true : false,
            'photos' => !empty($photos) ? json_encode($photos) : null,
        ]);



        // ✅ Si au moins une sous-sous-catégorie est sélectionnée
        if (!empty($this->sous_souscategories_ids)) {
            $produit->sousSousCategories()->sync($this->sous_souscategories_ids);

        }

        $this->reset([
            'nom','reference','prix','points_achat','points_vente','photo',
            'photos','description','id_categorie','id_brand','id_sous_categorie',
            'id_gamme','sous_souscategories_ids','trend'
        ]);

        $this->dispatch('resetSousSousCategories');
        session()->flash('success', 'Produit ajouté avec succès ✅');
    }
    public function removePrix($id)
    {
        autres_prix::where("id", $id)->where('id_produit', $this->produit->id)->delete();
        session()->flash('add-success', 'Prix supprimé avec succès');
    }

    public function update_produit()
    {
        if (!$this->produit) return;

        $this->validate([
            'nom' => 'required|string',
            'prix' => 'required|numeric',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:51200',
            'photos' => 'nullable|array',
            'photos.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:51200',
            'description' => 'nullable|string',
            'id_categorie' => 'required|exists:categories,id',
            'id_brand' => 'required|exists:brands,id',
            'best_sellers' => 'nullable|boolean',
            'masquer_produit' => 'nullable|boolean',
            'pris_order' => 'nullable|boolean',
            'id_sous_categorie' => 'nullable|exists:souscategories,id',
            'sous_souscategories_ids.*' => 'exists:sous_souscategories,id', // ✅ ajout important
            'id_gamme' => 'nullable|exists:gammes,id',
            'points_achat' => 'required|numeric',
            'points_vente' => 'required|numeric',
            'stock' => 'nullable|numeric|min:0',
            'trend' => 'nullable|boolean',

        ]);
        $stockValue = $this->stock ?: 0;


        $this->produit->update([
            'nom' => $this->nom,
            'prix' => $this->prix,
            'points_achat' => $this->points_achat,
            'points_vente' => $this->points_vente,
            'description' => $this->description,
            'id_categorie' => $this->id_categorie,
            'id_brand' => $this->id_brand,
            'best_sellers' => $this->best_sellers ? true : false,
            'masquer_produit' => $this->masquer_produit ? true : false,
            'pris_order'=>$this->pris_order? true : false,

            'id_sous_categorie' => $this->id_sous_categorie,
            'gamme_id' => $this->id_gamme,
            'stock' => $stockValue, // ✅ si vide, met 0
            'trend' => $this->trend ? 1 : 0,
            ]);

        if ($this->photo) {

            if ($this->produit->photo) {
                Storage::disk('public')->delete($this->produit->photo);
            }

            $this->produit->photo = $this->saveAsWebp($this->photo);
            $this->produit->save();
        }

        $this->saveGallery();
        $this->produit->save();

        // ✅ Synchroniser les sous-sous-catégories
        if (!empty($this->sous_souscategories_ids)) {
            $this->produit->sousSousCategories()->sync($this->sous_souscategories_ids);
            \Log::info('🔁 Sous-sous-catégories synchronisées :', $this->sous_souscategories_ids);
        } else {
            $this->produit->sousSousCategories()->detach();
            \Log::info('⚠️ Aucune sous-sous-catégorie sélectionnée — détachées');
        }
        $this->resetInput();
        Log::info("page:". $this->page);
        return redirect()->route('produits', ['page' => $this->page])
            ->with('success', "Produit modifié avec succès");
    }

    public function resetInput()
    {
        $this->nom = '';
        $this->prix = '';
        $this->photo = '';
        $this->photos = '';
        $this->id_sous_categorie = null;
        $this->id_sous_sous_categorie = null;
    }


    public function confirmDeleteAll()
    {
        $this->confirmingDeleteAll = true;
    }

    public function deleteAll()
    {
        try {
            // Supprimer tous les produits
            produits::query()->delete();

            // Message de succès
            session()->flash('success', '✅ Tous les produits ont été supprimés avec succès !');

            // Réinitialiser l’état du bouton de confirmation
            $this->confirmingDeleteAll = false;

            // Rafraîchir la liste
            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', '❌ Erreur : ' . $e->getMessage());
        }
    }

}
