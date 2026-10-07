<?php

namespace App\Livewire\Categories;

use App\Models\categories;
use App\Models\Souscategorie;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Souscategories extends Component
{
    use WithFileUploads;


    public $nom, $photo, $id_categorie, $categorie_selected;
    public $souscategorie_id; // 🆕 pour savoir si on modifie
    protected $listeners = ['closeUpdateCategorie' => '$refresh'];


    public function render()
    {
        $sous_categories = Souscategorie::orderBy("order_column", "asc")->get();
        $categories = Categories::orderBy("order_column", "asc")->get();

        return view('livewire.categories.souscategories', [
            'sous_categories' => $sous_categories,
            'categories' => $categories
        ]);
    }



    public function save()
    {
        $this->validate([
            'nom' => 'required|string|min:3|max:255',
            'id_categorie' => 'nullable|exists:categories,id',
            'photo' => 'nullable|image|max:4048',
        ]);

        $cat = new Souscategorie();
        $cat->nom = $this->nom;
        $cat->id_categorie = $this->id_categorie;

        if ($this->photo) {
            $cat->photo = $this->photo->store('sous-categories', 'public');
        }

        $cat->save();

        $this->reset(['nom', 'id_categorie', 'photo']);
        session()->flash('success', '✅ Sous-catégorie créée avec succès');
    }
    public function edit($id)
    {
        $sousCat = Souscategorie::find($id);

        if ($sousCat) {
            $this->souscategorie_id = $sousCat->id;
            $this->nom = $sousCat->nom;
            $this->id_categorie = $sousCat->id_categorie;
            $this->photo = null; // reset l'upload
        }
    }
    public function update()
    {
        $this->validate([
            'nom' => 'required|string|min:3|max:255',
            'id_categorie' => 'nullable|exists:categories,id',
            'photo' => 'nullable|image|max:4048',
        ]);

        $sousCat = Souscategorie::find($this->souscategorie_id);

        if (!$sousCat) {
            session()->flash('error', '❌ Sous-catégorie introuvable');
            return;
        }

        $sousCat->nom = $this->nom;
        $sousCat->id_categorie = $this->id_categorie;

        if ($this->photo) {
            // Supprime l’ancienne image si elle existe
            if ($sousCat->photo) {
                Storage::disk('public')->delete($sousCat->photo);
            }
            $sousCat->photo = $this->photo->store('sous-categories', 'public');
        }

        $sousCat->save();

        $this->reset(['nom', 'id_categorie', 'photo', 'souscategorie_id']);
        session()->flash('success', '✅ Sous-catégorie mise à jour avec succès');
    }




    public function delete($id){
        $categorie = Souscategorie::find($id);
        if($categorie){
            if ($categorie->photo) {
                Storage::disk('public')->delete($categorie->photo);
            }
            $categorie->delete();
            //flash message
            session()->flash('success', 'Sous-catégorie supprimée avec succès!');
        }
    }



}
