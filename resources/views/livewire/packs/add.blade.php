<div>
    @include('components.alert')
    <form wire:submit="save">
        <div class="row">
            <div class="col-sm-4">
                <h6>
                    Information sur le pack
                </h6>
                <br>
                <div class="mb-3">
                    <label for="">Désignation du pack</label>
                    <input type="text" name="nom" id="nom" wire:model="nom" class="form-control"
                        placeholder="Nom a afficher">
                    @error('nom')
                        <span class="small text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-12 col-sm-6  mb-3">
                        <label for="">Référence du pack</label>
                        <input type="text" name="reference" id="reference" wire:model="reference"
                            class="form-control" placeholder="reference a afficher">
                        @error('reference')
                            <span class="small text-danger"> {{ $message }} </span>
                        @enderror
                    </div>
                    <div class="col-12 col-sm-6  mb-3">
                        <label for="">Prix du pack</label>
                        <input type="number" step="0.0001" name="prix" id="prix" wire:model="prix"
                            class="form-control" placeholder="prix a afficher">
                        @error('prix')
                            <span class="small text-danger"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
            </div>



            <div class="col-sm-5">
                <h6>Contenu pack</h6>
                <br>
                <div class="table-responsive-sm">
                <table class="table table-striped dt-responsive nowrap w-100">
                    <thead class="table-dark">
                        <tr>
                            <td>Nom</td>
                            <td>Quantité</td>
                            <td>Prix</td>
                            <td>

                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($panier ?? [] as $selection)
                        
                            <tr>
                                <td> {{ $selection['name'] }} </td>
                                <td> {{ $selection['quantity'] }} </td>
                                <td> 
                                    {{ $selection['quantity'] *  $selection['price']  }} 
                                    <x-devise></x-devise>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-danger" type="button" wire:click="delete_from_cart({{ $selection['id'] }})">
                                        <i class="ri-close-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="text-center p-2">
                                        Aucun produit dans le panier.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>



            <div class="col-sm-3">
                <h6>
                    Recherche d'article
                </h6>
                <br>
                <div class="card p-2">
                    <label for="">
                        Recherche d'un produit
                    </label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" wire:model="key"
                            placeholder="Recherche d'article ( Nom, Description,Réference )">
                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="button" wire:click="recherche()">
                                Rechercher
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive-sm">
                    @if ($propositions)
                    <table class="table table-striped dt-responsive nowrap w-100">
                        <tbody>
                            @forelse ($propositions as $item)
                                <tr>
                                    <td> 
                                        {{ $item->nom }} 
                                        <div>
                                            {{ $item->prix }} <x-devise></x-devise>
                                        </div>
                                    </td>
                                    <td class="my-auto">
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control"
                                                wire:model.lazy="produits.{{ $item->id }}"
                                                style="width: 1px !important" placeholder="Quantité">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" wire:click="add_cart">
                                                    <i class="ri-add-circle-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">
                                        <div class="text-center p-3">
                                            <p>Aucun produit trouvé</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @endif
                  
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary">
                <span wire:loading>
                    <img src="https://i.gifer.com/ZKZg.gif" height="15" alt="" srcset="">
                </span>
                Enregistrer le pack
            </button>
        </div>
    </form>
</div>
