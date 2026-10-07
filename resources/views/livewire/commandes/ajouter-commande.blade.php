<div>
    <div>
        @include('components.alert')
        <div class="table-responsive-sm">
            <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                <thead class="table-dark">
                    <tr>
                        <th>Produit</th>
                        <th>quantite</th>
                        <th>Prix</th>
                        <th>Montant</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                    @endphp
                    @forelse ($paniers ?? [] as $key => $panier)
                        <tr>
                            <td>
                                {{ $panier['nom'] }}
                            </td>
                            <td> {{ $panier['quantite'] ?? 1 }} </td>
                            <td> {{ $panier['prix'] }} <x-devise></x-devise> </td>
                            <td> {{ $panier['total'] }} <x-devise></x-devise> </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-danger" type="danger"
                                    wire:click="delete_from_session({{ $key }})">
                                    <i class="ri-delete-bin-6-line"></i>
                                </button>
                            </td>
                        </tr>
                        @php
                            $total += $panier['total'];
                        @endphp
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="p-3 text-center">
                                    <i class="ri-shopping-cart-2-line"></i>
                                    <br>
                                    Aucun produit dans votre panier!
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <br>
    <hr>
    <br>
    @if ($paniers)
        <div class="d-flex justify-content-between p-2 card" style="background-color: #027461;color:white;">
            <h5 class="header-title">
                Finalisation de la commande
            </h5>
        </div>
        <div class="modal-body">
            <form wire:submit="order">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="">Nom *</label>
                                    <input type="text" placeholder="Nom du client" required wire:model="nom"
                                        class="form-control">
                                    @error('nom')
                                        <span class="small text-danger" role="alert"> {{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="">prenom</label>
                                    <input type="text" placeholder="prenom du client" wire:model="prenom"
                                        class="form-control">
                                    @error('prenom')
                                        <span class="small text-danger" role="alert"> {{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="">Adresse *</label>
                                    <input type="text" placeholder="Adresse du client" required wire:model="adresse"
                                        class="form-control">
                                    @error('adresse')
                                        <span class="small text-danger" role="alert"> {{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="">Pays *</label>
                                    <select wire:model="pays" class="form-control" required>
                                        <option value="">Select a country</option>
                                        <option value="United Arab Emirates">United Arab Emirates</option>
                                        <option value="Algerie">Algerie</option>
                                        <option value="Libye">Libye</option>
                                    </select>
                                    @error('pays')
                                        <span class="small text-danger" role="alert"> {{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="">Numéro de téléphone *</label>
                                <input type="tel" placeholder="Numéro de téléphone du client" wire:model="phone"
                                    class="form-control">
                                @error('phone')
                                    <span class="small text-danger" role="alert"> {{ $message }} </span>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="">Emirate *</label>
                                <select wire:model="gouvernorat" class="form-control" required>
                                    <option value="">Emirate</option>
                                    @foreach ($gouvernoratsTunisie as $gouvernorat)
                                        <option value="{{ $gouvernorat->id }}">
                                            {{ $gouvernorat->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('gouvernorat')
                                    <span class="small text-danger" role="alert"> {{ $message }} </span>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="">Canal de vente *</label>
                                <select wire:model="canal_vente" class="form-control" required>
                                    <option value=""></option>
                                    <option value="Site Web">Site Web</option>
                                    <option value="Instagram">instagram</option>
                                    <option value="Facebook ">facebook </option>
                                    <option value="Tiktok">tiktok</option>
                                    <option value="Bouche à oreille">Bouche à oreille</option>
                                </select>
                                @error('gouvernorat')
                                    <span class="small text-danger" role="alert"> {{ $message }} </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3 mb-1">
                            <div class="mb-1">
                                <input type="checkbox" class="form-check-input" wire:model="frais">
                                Appliquer les frais de livraison. <b> ( + {{ $config->getFrais() }} <x-devise></x-devise> )</b>
                                @error('frais')
                                    <br> <span class="small text-danger" role="alert"> {{ $message }} </span>
                                @enderror
                            </div>
                            <div class="mb-1">
                                <input type="checkbox" class="form-check-input" wire:model="tva">
                                Appliquer les frais de tva  <b>( + {{ $config->getTva() }} <x-devise></x-devise> )</b> 
                                @error('tva')
                                    <br> <span class="small text-danger" role="alert"> {{ $message }} </span>
                                @enderror
                            </div>
                            <div class="mb-1">
                                <input type="checkbox" class="form-check-input" wire:model="timbre">
                                Appliquer les frais de timbre. <b>( + {{ $config->getTimbre() }} <x-devise></x-devise> )</b> 
                                @error('timbre')
                                    <br> <span class="small text-danger" role="alert"> {{ $message }} </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <h6>
                            Reherche d'un client déja enregistré !
                        </h6>
                        <div class="mb-3">
                            <input type="text" wire:model.live="recherche" placeholder="Nom, prenom ,téléphone"
                                class="form-control">
                        </div>
                        <div>
                            <table class="table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Téléphone</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>
                                            <span wire:loading>
                                                <img src="https://i.gifer.com/ZKZg.gif" height="15" alt=""
                                                    srcset="">
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($clients as $client)
                                        <tr>
                                            <td>{{ $client->phone }}</td>
                                            <td>{{ $client->nom }}</td>
                                            <td>{{ $client->prenom }}</td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-sm"
                                                    wire:click="import({{ $client }})">
                                                    <i class="ri-import-line"></i>
                                                    Importer
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">
                                                <div class="alert">
                                                    Aucun résultat disponible
                                                    @if ($recherche)
                                                        " <b> {{ $recherche }} </b> "
                                                    @endif
                                                    !
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <br>
                <hr>

                <div class="d-flex justify-content-between">
                    <div>
                        <h6>
                            Montant de la commande : <b> {{ $total }} <x-devise></x-devise></b>
                        </h6>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="ri-check-double-line"></i>
                            Valider cette commande
                        </button>
                    </div>
                </div>

            </form>
    @endif

</div>
