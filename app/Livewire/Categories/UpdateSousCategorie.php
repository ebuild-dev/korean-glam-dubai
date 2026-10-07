<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Souscategorie;

use Illuminate\Support\Facades\Storage;

class UpdateSousCategorie extends Component
{
    use WithFileUploads;

    public $sousCategorieId;
    public $sousCategorie;
    public $nom, $photo, $id_categorie;

    // 👂 Écouteur pour ouvrir la modale
    protected $listeners = ['openUpdateSousCategorie' => 'open'];

    public function open($id)
    {
        $this->reset(['nom', 'photo', 'id_categorie']); // Nettoyer avant ouverture

        $this->sousCategorieId = $id;
        $this->sousCategorie = Souscategorie::find($id);

        if ($this->sousCategorie) {
            $this->nom = $this->sousCategorie->nom;
            $this->id_categorie = $this->sousCategorie->id_categorie;
        }
    }

    public function render()
    {
        $categories = \App\Models\categories::orderBy('order_column', 'asc')->get();
        return view('livewire.categories.update-sous-categorie', compact('categories'));
    }

    public function updateSousCategorie()
    {
        $this->validate([
            'nom' => 'required|string|min:3|max:255',
            'id_categorie' => 'nullable|exists:categories,id',
            'photo' => 'nullable|image|max:4048',
        ]);

        $sousCat = Souscategorie::find($this->sousCategorieId);
        if (!$sousCat) {
            session()->flash('error', '❌ Sous-catégorie introuvable.');
            return;
        }

        $sousCat->nom = $this->nom;
        $sousCat->id_categorie = $this->id_categorie;

        if ($this->photo) {
            // Supprime l'ancienne photo si elle existe
            if ($sousCat->photo) {
                Storage::disk('public')->delete($sousCat->photo);
            }
            $sousCat->photo = $this->photo->store('sous-categories', 'public');
        }

        $sousCat->save();

        // 🔁 Rafraîchir la liste après update
        $this->dispatch('closeUpdateSousCategorie');
        session()->flash('success', '✅ Sous-catégorie mise à jour avec succès');
    }
}
