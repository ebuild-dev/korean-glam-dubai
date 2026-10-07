<?php

namespace App\Livewire\Categories;

use App\Models\categories as ModelsCategories;
use Livewire\Component;
use Livewire\WithFileUploads;

class Categories extends Component
{
    use WithFileUploads;
    public $nom, $description, $photo, $categorie_selected;
    protected $listeners = ['closeUpdateCategorie' => '$refresh'];

    public function render()
    {
        $categories = ModelsCategories::Orderby("order_column", "asc")->get();
        return view('livewire.categories.categories')
            ->with('categories', $categories);
    }



    public function updateKBeauty($id){
        $categorie = ModelsCategories::find($id);
        if($categorie){
            $categorie->is_k_beauty = !$categorie->is_k_beauty;
            $categorie->save();
        }
    }

    public function save()
    {
        $this->validate([
            'nom' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:3',
            'photo' => 'nullable|image|max:4048',
        ]);

        $cat = new ModelsCategories();
        $cat->nom = $this->nom;
        $cat->description = $this->description;
        if ($this->photo) {
            $cat->photo = $this->photo->store('categories', 'public');
        }
        $cat->save();

        $this->reset(['nom', 'description']);

        // flash success message
        session()->flash('success', 'La catégorie a été créée avec succès');
    }





    public function delete($id){
        $categorie = ModelsCategories::find($id);
        if($categorie){
            $categorie->delete();
            //flash message
            session()->flash('success', 'Catégorie supprimée avec succès!');
        }
    }



}
