<?php

namespace App\Livewire\Gammes;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Gamme;
use Illuminate\Support\Facades\Storage;

class Update extends Component
{
    use WithFileUploads;

    public $gamme_id;
    public $nom, $description, $order_column, $photo, $new_photo;

    protected $listeners = ['openUpdateGamme' => 'loadGamme'];

    protected $rules = [
        'nom' => 'required|string|max:255',
        'description' => 'nullable|string',
        'order_column' => 'nullable|integer',
        'new_photo' => 'nullable|image|max:2048',
    ];

    public function loadGamme($id)
    {
        $this->gamme_id = $id;
        $gamme = Gamme::findOrFail($id);
        $this->nom = $gamme->nom;
        $this->description = $gamme->description;
        $this->order_column = $gamme->order_column;
        $this->photo = $gamme->photo;
    }

    public function update()
    {
        $this->validate();

        $gamme = Gamme::findOrFail($this->gamme_id);

        $photoPath = $this->photo;

        if ($this->new_photo) {
            if ($this->photo && Storage::disk('public')->exists($this->photo)) {
                Storage::disk('public')->delete($this->photo);
            }
            $photoPath = $this->new_photo->store('gammes', 'public');
        }

        $gamme->update([
            'nom' => $this->nom,
            'description' => $this->description,
            'order_column' => $this->order_column ?? 0,
            'photo' => $photoPath,
        ]);

        session()->flash('message', 'Gamme mise à jour avec succès ✅');
        $this->dispatch('closeModal'); // si tu veux fermer le modal après succès
    }

    public function render()
    {
        return view('livewire.gammes.update');
    }
}
