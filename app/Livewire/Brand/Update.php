<?php

namespace App\Livewire\Brand;

use App\Models\Brand;
use App\Models\categories;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class Update extends Component
{
    use WithFileUploads;
    public $marque,$nom,$photo;
    protected $listeners = ['openUpdateMarque' => 'open'];

    public function open($id){
        $this->marque = Brand::find($id);
        $this->nom = $this->marque->brandname;
    }

    public function render()
    {
        return view('livewire.brand.update');
    }


    public function update(){
        $this->validate(
            [
                "nom" => "string|required|max:120",
                "photo" => "nullable|image|max:2048"
            ]
        );

        $new = $this->marque;
        $new->brandname = $this->nom;
        $new->brandurl = Str::slug($this->nom);
        if($this->photo){
            if($new->photourl){
                Storage::delete($new->photourl);
            }
            $new->photourl = $this->photo->store('public/brands');
        }
        $new->save();
        session()->flash('success','Marque modifiée avec succès');
        $this->dispatch('closeUpdateMarque');
    }
}
