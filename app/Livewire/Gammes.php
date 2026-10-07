<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Gamme;

class Gammes extends Component
{
    use WithFileUploads;

    public $nom, $description, $order_column, $photo;

    protected $rules = [
        'nom' => 'required|string|max:255',
        'description' => 'nullable|string',
        'order_column' => 'nullable|integer',
        'photo' => 'nullable|image|max:2048',
    ];

    public function save()
    {
        $this->validate();

        $photoPath = $this->photo ? $this->photo->store('gammes', 'public') : null;

        Gamme::create([
            'nom' => $this->nom,
            'description' => $this->description,
            'order_column' => $this->order_column ?? 0,
            'photo' => $photoPath,
        ]);

        session()->flash('message', 'Gamme ajoutée avec succès ✅');
        $this->reset(['nom', 'description', 'order_column', 'photo']);
    }

    public function delete($id)
    {
        $gamme = Gamme::findOrFail($id);
        if ($gamme->photo && Storage::disk('public')->exists($gamme->photo)) {
            Storage::disk('public')->delete($gamme->photo);
        }
        $gamme->delete();

        session()->flash('message', 'Gamme supprimée ❌');
    }

    public function render()
    {
        $gammes = Gamme::withCount('produits')->orderBy('order_column')->get();
        return view('livewire.gammes', compact('gammes'));
    }
}
