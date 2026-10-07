<div class="row">
    <div class="col-sm-12">
        <div class="card radius-15">
            <div class="card-body">
                <div class="card-title mb-3">
                    <h5 class="mb-0">Liste des commentaires</h5>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                        <tr>
                            <th>Utilisateur</th>
                            <th>Produit</th>
                            <th>Photo de Produit</th>
                            <th>Commentaire</th>
                            <th>Photo</th>
                            <th>Validé</th>
                            <th>Création</th>
                            <th class="text-end">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($comments as $comment)
                            <tr>
                                <td>{{ $comment->user->nom }}</td>
                                <td>{{ $comment->produit->nom ?? 'Produit supprimé' }}</td>
                                <td>
                                    @if($comment->produit && $comment->produit->photo)
                                        <img src="{{ Storage::url($comment->produit->photo) }}" alt="Produit" style="width:50px; height:50px; object-fit:cover;" class="rounded">
                                    @else
                                        Produit supprimé
                                    @endif
                                </td>

                                <td>{{ $comment->commentaire }}</td>
                                <td>
                                    @if($comment->photo)
                                        <img src="{{ Storage::url($comment->photo) }}" alt="photo" style="width:50px;">
                                    @endif
                                </td>
                                <td>
                                    <!-- Bouton principal Valider / Non -->
                                    <button class="btn btn-sm {{ $comment->valide ? 'btn-success' : 'btn-warning' }}"
                                            type="button" wire:click="$set('commentIdToValidate', {{ $comment->id }})">
                                        {{ $comment->valide ? 'Oui' : 'Non' }}
                                    </button>

                                    <!-- Boutons Confirmer / Annuler affichés seulement si le commentaire est sélectionné -->
                                    @if($commentIdToValidate === $comment->id)
                                        <button class="btn btn-sm btn-success" type="button" wire:click="toggleValidate({{ $comment->id }})">
                                            Confirmer
                                        </button>
                                        <button class="btn btn-sm btn-secondary" type="button" wire:click="$set('commentIdToValidate', null)">
                                            Annuler
                                        </button>
                                    @endif
                                </td>

                                <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-end">
                                    <!-- Bouton Supprimer -->
                                    <button class="btn btn-sm btn-danger" type="button" wire:click="$set('commentIdToDelete', {{ $comment->id }})">
                                        Supprimer
                                    </button>

                                    <!-- Bouton Confirmer (affiché seulement si l'id correspond) -->
                                    @if($commentIdToDelete === $comment->id)
                                        <button class="btn btn-sm btn-success" type="button" wire:click="delete({{ $comment->id }})">
                                            Confirmer
                                        </button>
                                        <button class="btn btn-sm btn-secondary" type="button" wire:click="$set('commentIdToDelete', null)">
                                            Annuler
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    Aucun commentaire trouvé.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

