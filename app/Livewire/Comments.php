<?php

namespace App\Livewire;

use App\Models\Comment;
use Livewire\Component;

class Comments extends Component

{
    public $comments;
    public $commentIdToValidate;
    public $commentIdToDelete = null;


    protected $listeners = ['refreshComments' => '$refresh'];

    public function mount()
    {
        $this->loadComments();
    }

    public function loadComments()
    {
        // Récupérer tous les commentaires avec l'utilisateur et le produit
        $this->comments = Comment::with(['user', 'produit'])->latest()->get();
    }

    public function toggleValidate($id)
    {
        $comment = Comment::find($id);
        if ($comment) {
            $comment->valide = !$comment->valide;
            $comment->save();
            $this->commentIdToValidate = null; // réinitialiser après confirmation
            $this->loadComments();
        }
    }

    public function delete($id)
    {
        $comment = Comment::find($id);
        if ($comment) {
            $comment->delete();
            $this->commentIdToDelete = null;
            $this->loadComments();
        }
    }


    public function render()
    {
        return view('livewire.comments');
    }
}
