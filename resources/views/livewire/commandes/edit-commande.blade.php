<div>
    <form wire:submit="save">
        @include('components.alert')
        <div class="row">
            <div class="col-sm-8">
                <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                    <thead class="table-dark">
                        <tr>
                            <th></th>
                            <th>Product</th>
                            <th>Prix</th>
                            <th>Qty</th>
                            <th>Sub-Total</th>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>@php
                        $total = 0;
                    @endphp
                        @foreach ($commande->contenus as $contenu)
                            <tr wire:key="contenu-{{ $contenu->id }}"> @php
                                    $total += $contenu->quantite * $contenu->prix_unitaire;
                                @endphp
                                <td>
                                    <img src="{{ $contenu->produit->FirstImage() }}" width="40 " height="40 "
                                        class="rounded shadow" alt="">

                                </td>
                                <td>
                                    {{ $contenu->produit->nom }}
                                </td>
                                <td>
                                    {{ $contenu->prix_unitaire }} AED
                                </td>
                                <td>
                                    @if ($commande->modifiable())
                                        <div class="input-group mb-3" style="width: 120px !important">
                                            <button class="btn btn-outline-secondary" type="button"
                                                wire:click="change({{ $contenu->id }}, {{ $contenu->quantite - 1 }})" @disabled($contenu->quantite <= 1)>-</button>
                                            <input type="text" readonly value="{{ $contenu->quantite }}"
                                                class="form-control">
                                            <button class="btn btn-outline-secondary" type="button"
                                                wire:click="change({{ $contenu->id }}, {{ $contenu->quantite + 1 }})">+</button>
                                        </div>
                                    @else
                                        {{ $contenu->quantite }}
                                    @endif

                                </td>
                                <td>
                                    {{ $contenu->quantite * $contenu->prix_unitaire }} AED
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger" type="button"
                                        wire:click="delete( {{ $contenu->id }})">
                                        X
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>
                                <img width="30" height="30"
                                    src="https://img.icons8.com/ios/30/40C057/delivery--v1.png" alt="delivery--v1" />
                            </td>
                            <td> <b>Frais de livraison</b> </td>
                            <td>{{ \App\Models\Config::first()->frais ?? 0 }} AED</td>
                            <td> 1 </td>

                            <td> {{$commande->montant_with_coupon() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-4">
                <h6>
                    Informations du client.
                </h6>
                <div class="row mb-3">
                    <div class="col">
                        <label for="">Nom *</label>
                        <input type="text" placeholder="Nom du client" required wire:model="nom"
                            class="form-control">
                        @error('nom')
                            <span class="small text-danger" role="alert"> {{ $message }} </span>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="">prenom</label>
                        <input type="text" placeholder="preom du client" wire:model="prenom" class="form-control">
                        @error('prenom')
                            <span class="small text-danger" role="alert"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="">Adresse *</label>
                    <input type="text" placeholder="Adresse du client" required wire:model="adresse"
                        class="form-control">
                    @error('adresse')
                        <span class="small text-danger" role="alert"> {{ $message }} </span>
                    @enderror
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
                        <select wire:model="id_gouvernorat" class="form-control" required>
                            <option value="">Emirate</option>
                            @foreach ($gouvernoratsTunisie as $gouvernorat)
                                <option value="{{ $gouvernorat->id }}">
                                    {{ $gouvernorat->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_gouvernorat')
                            <span class="small text-danger" role="alert"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                <div class="mt-3 mb-1">
                    <div class="mb-1">
                        <input type="checkbox" class="form-check-input" @checked($commande->frais > 0) wire:model="frais">
                        Appliquer les frais de livraison . <b> ( + {{ $config->getFrais() }} <x-devise></x-devise>
                            )</b>
                        @error('frais')
                            <br> <span class="small text-danger" role="alert"> {{ $message }} </span>
                        @enderror
                    </div>
                    <div class="mb-1">
                        <input type="checkbox" class="form-check-input" @checked($commande->tva > 0) wire:model="tva">
                        Appliquer les frais de tva <b>( + {{ $config->getTva() }} <x-devise></x-devise> )</b>
                        @error('tva')
                            <br> <span class="small text-danger" role="alert"> {{ $message }} </span>
                        @enderror
                    </div>
                    <div class="mb-1">
                        <input type="checkbox" class="form-check-input" @checked($commande->timbre > 0)
                            wire:model="timbre">
                        Appliquer les frais de timbre. <b>( + {{ $config->getTimbre() }} <x-devise></x-devise> )</b>
                        @error('timbre')
                            <br> <span class="small text-danger" role="alert"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div>
            <button type="button" wire:click='save' class="btn btn-primary">
                <i class="ri-check-double-line"></i>
                Enregistrer
            </button>
           {{-- <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modal-end">
                Regénérer la commande
            </button>--}}
            <span wire:loading>
                <img src="https://i.gifer.com/ZKZg.gif" height="15" alt="" srcset="">
            </span>
        </div>
    </form>




    <div wire:ignore.self class="modal fade" id="modal-update" tabindex="-1" role="dialog"
        aria-labelledby="modal-update-title" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="modal-update-title">Modifier les articles</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    @include('components.alert')
                    <p class="text-muted small">Ajoutez des articles, modifiez les quantités, puis cliquez sur Enregistrer.</p>
                <div class="row g-3">
                <div class="col-lg-7">
                @if ($commande->modifiable())
                    <div class="mb-3 border rounded p-3">
                        <label for="recherche-produit" class="form-label">Ajouter un article</label>
                        <input id="recherche-produit" type="search" class="form-control mb-2"
                            placeholder="Rechercher par nom ou référence"
                            wire:model.live.debounce.300ms="rechercheProduit">
                            <ul class="list-group mt-2" style="max-height: 55vh; overflow-y: auto;">
                                @forelse ($produitsTrouves as $produit)
                                    <li class="list-group-item d-flex flex-wrap justify-content-between align-items-center gap-2"
                                        wire:key="ajout-produit-{{ $produit->id }}">
                                        <img src="{{ $produit->FirstImage() }}" alt="{{ $produit->nom }}"
                                            width="56" height="56" loading="lazy"
                                            class="rounded border flex-shrink-0" style="object-fit: contain;">
                                        <span class="flex-grow-1" style="min-width: 0; overflow-wrap: anywhere;">{{ $produit->nom }} — {{ $produit->reference }}<br>
                                            <small>{{ $produit->getPrice() }} AED · Stock : {{ $produit->stock }}</small>
                                            @if (isset($quantitesModal[$produit->id]))
                                                <br><span class="badge bg-success">Dans la sélection : {{ $quantitesModal[$produit->id] }}</span>
                                            @endif
                                        </span>
                                        <div class="d-flex align-items-center gap-2">
                                        <label for="ajout-quantite-{{ $produit->id }}" class="visually-hidden">Quantité à ajouter pour {{ $produit->nom }}</label>
                                        <input id="ajout-quantite-{{ $produit->id }}" type="number" min="1" step="1"
                                            class="form-control form-control-sm" style="width: 70px;"
                                            wire:model="quantitesRecherche.{{ $produit->id }}">
                                        <button type="button" class="btn btn-sm btn-primary flex-shrink-0"
                                            wire:click="selectionnerArticle({{ $produit->id }})"
                                            wire:loading.attr="disabled" @disabled($produit->stock < 1)>Ajouter</button>
                                        </div>
                                        @error('quantitesRecherche.' . $produit->id)
                                            <small class="text-danger w-100" role="alert">{{ $message }}</small>
                                        @enderror
                                    </li>
                                @empty
                                    <li class="list-group-item">Aucun article trouvé.</li>
                                @endforelse
                            </ul>
                        @if ($plusDeProduits)
                            <button type="button" class="btn btn-outline-primary btn-sm mt-2 w-100"
                                wire:click="voirPlusProduits" wire:loading.attr="disabled">Afficher plus de produits</button>
                        @endif
                    </div>
                @endif
                @error('quantiteAjout')
                    <div class="text-danger mb-2" role="alert">{{ $message }}</div>
                @enderror
                @error('quantitesModal')
                    <div class="text-danger mb-2" role="alert">{{ $message }}</div>
                @enderror
                </div>
                <div class="col-lg-5">
                <h6>Articles sélectionnés <span class="badge bg-primary">{{ count($quantitesModal) }}</span></h6>
                <div class="list-group">
                    @foreach ($articlesModal as $article)
                        <div class="list-group-item d-flex align-items-center gap-3" wire:key="modal-article-{{ $article->id }}">
                            <img src="{{ $article->FirstImage() }}" alt="{{ $article->nom }}" width="56" height="56"
                                class="rounded border flex-shrink-0" style="object-fit: contain;">
                            <span class="flex-grow-1">{{ $article->nom }}<br><small>{{ $article->reference }}</small></span>
                            <div style="width: 100px;" class="flex-shrink-0">
                                <label for="modal-quantite-{{ $article->id }}">Quantité</label>
                                <input id="modal-quantite-{{ $article->id }}" type="number" min="1" step="1" class="form-control"
                                    wire:model="quantitesModal.{{ $article->id }}" @disabled(!$commande->modifiable())>
                                @error('quantitesModal.' . $article->id)
                                    <small class="text-danger" role="alert">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    @endforeach
                </div>
                </div>
                </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    @if ($commande->modifiable())
                        <button type="button" class="btn btn-primary" wire:click="enregistrerArticles"
                            wire:loading.attr="disabled">Enregistrer</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Center modal content -->
    <div class="modal fade" id="modal-end" tabindex="-1" role="dialog" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">1
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="myCenterModalLabel">
                        Regénérer la commande
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-danger">
                        <b>
                            <p>
                                1. La commande précedente restera dans la plate-fome JAX mais vous devez la supprimer.
                            </p>
                            <p>
                                2. La réferance changera.
                            </p>
                        </b>
                    </div>
                    <div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                            <button type="button" wire:click='reload' class="btn btn-primary">Oui</button>
                            <span wire:loading>
                                <img src="https://i.gifer.com/ZKZg.gif" height="15" alt=""
                                    srcset="">
                            </span>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->



</div>
