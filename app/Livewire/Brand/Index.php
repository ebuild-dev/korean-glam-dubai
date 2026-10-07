<?php

namespace App\Livewire\Brand;

use App\Models\Brand;
use App\Models\categories;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads;
    public $nom,$categorie_id,$photo;
    protected $listeners = ['closeUpdateMarque' => '$refresh'];

    public function render()
    {
        $marques = Brand::Orderby("id","DESC")->get();
        return view('livewire.brand.index')
        ->with("marques",$marques);
    }
    


    public function save(){
        $this->validate(
            [
                "nom" => "string|required|max:120",
                "photo" => "required|image|max:2048"
            ]
        );

        $new = New Brand();
        $new->brandname = $this->nom;
        $new->brandurl = Str::slug($this->nom);
        $new->photourl = $this->photo->store('brands','public');
        $new->save();
        $this->reset(['nom','categorie_id','photo']);
        session()->flash('success','Marque ajoutée avec succès');
    }



    public function destroy($brand_id){
        $brand = Brand::find($brand_id);
        if($brand){
            $brand->delete();
            session()->flash('success','Marque supprimée avec succès');
        }
    }



}
