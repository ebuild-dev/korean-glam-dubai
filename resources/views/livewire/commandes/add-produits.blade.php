<div>
    <div class="row">
        <div class="col-sm-5">
            <form wire:submit='save'>
                <div class="mb-3">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" required placeholder="Réference" wire:model='reference'>
                        <input type="number" min="1" class="form-control" required placeholder="Quantité"
                            wire:model='quantite'>
                        <button class="btn btn-primary" type="submit">
                            Ajouter
                        </button>
                    </div>
                    @error('reference')
                        <span class="small text-danger">
                            {{ $message }}
                        </span>
                    @enderror
                    @error('quantite')
                        <span class="small text-danger">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </form>
            <input type="text" name="key" wire:model.live='key' class="form-control"
                placeholder="Recherche d'un produit" id="">
            <table class="table">
                @if ($listes)
                    @forelse ($listes as $liste)
                        <tr>
                            <td> {{ $liste->nom }} </td>
                            <td> {{ $liste->reference }} </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-dark"
                                    wire:click="ajouter({{ $liste->reference }})">
                                    <i class='bx bx-upload'></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                @endif
            </table>
        </div>
        <div class="col-sm-7">
            <table class="table">
                <thead class="table-dark">
                    <tr>
                        <th>Réference</th>
                        <th>Quantité</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($produits as $produit)
                        <tr>
                            <td>
                                {{ $produit['reference'] }}
                            </td>
                            <td>
                                {{ $produit['quantite'] }}
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-danger"
                                    wire:click="delete('{{ $produit['reference'] }}')">
                                    Supprimer
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                <div class="text-center p-3">
                                    Aucun produit trouvé.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="alert alert-danger small">
        1. Veuillez garder a l'esprit que si vous fermer cette fenetre sans sauvegarder, vous articles ne seront pas
        enregistrés.
    </div>
    @if ($produits)
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                Fermer
            </button>
            <button type="button" wire:click='Terminer' class="btn btn-primary">
                Sauvegarder en tant que nouvelle commande
            </button>
        </div>
    @endif
</div>
