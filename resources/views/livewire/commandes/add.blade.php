<div>
    <div class="mb-3">
        <div class="input-group mb-3">
            <input type="text" placeholder="Recherche d'un produit" wire:model.live="key" class="form-control">
            <div class="input-group-append" wire:loading>
                <span class="input-group-text">
                    <span>
                        <img src="https://i.gifer.com/ZKZg.gif" height="15" alt="" srcset="">
                    </span>
                </span>
            </div>
        </div>
    </div>
    @include('components.alert')
    <div>
        <table class="w-100">
            @if ($produits)
                @forelse ($produits as $key=>$produit)
                    <tr>
                        <td style="width: 52px;">
                            <div class="card-image-sugest-commande">
                                <img src="{{ $produit['photo'] }}" alt="{{ $produit['nom'] }}" srcset="">
                            </div>
                        </td>
                        <td class="my-auto">
                            {{ Str::limit($produit['nom'], 30) }}
                            <div class="small">
                                {{ $produit['prix'] }} <x-devise></x-devise>
                            </div>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-primary" type="button"
                                wire:click="ajouterProduit({{ $produit['id'] }})">
                                <i class="ri-add-circle-line"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">
                            <div class="text-center p-2">
                                <i> <i class="ri-information-line"></i> Aucun résultat.</i>
                            </div>
                        </td>
                    </tr>
                @endforelse
            @endif
            @if ($id_select_produit)
                <div class="card p-2 card-add-qte-commande">
                    <b>Quantité</b>
                    <div class="input-group mb-3">
                        <input type="number" min="1" value="1" wire:model="quantite" class="form-control"
                            placeholder="Quantité">
                        <div class="input-group-append">
                            <button class="btn btn-dark" type="submit" wire:click='save()'>
                                Ajouter
                            </button>
                            <button class="btn btn-danger" type="submit" wire:click='reset_add()'>
                                <i class="ri-close-circle-line"></i>
                            </button>
                        </div>
                    </div>
                    @error('quantite')
                        <span class="small text-danger">
                            {{ $message }}
                        </span>
                    @enderror
                    @error('id_select_produit')
                        <span class="small text-danger">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            @endif
        </table>
    </div>

    <style>
        .card-image-sugest-commande {
            height: 50px;
            width: 50px;
            border-radius: 4px;
            overflow: hidden;
        }

        .card-image-sugest-commande img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-add-qte-commande {
            border-right: solid 5px #212529;
            border-left: solid 5px #027461;
            background-color: #20dbbc11;
        }
    </style>
</div>
