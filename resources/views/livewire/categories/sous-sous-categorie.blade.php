<div class="row">
    <div class="col-sm-8">
        <div class="card radius-15">
            <div class="card-body">
                <div class="card-title">
                    <h5 class="mb-0 my-auto">
                        Liste des sous-sous-catégories
                    </h5>
                </div>
                <div class="table-responsive-sm">
                    <table id="basic-datatable" class="table sortable-list table-striped dt-responsive nowrap w-100">
                        <thead class="table-dark cusor">
                        <tr>
                            <th>Nom</th>
                            <th>Catégorie</th>
                            <th>Sous-catégorie</th>

                            <th>Création</th>
                            <th style="text-align: right;">
                                    <span wire:loading>
                                        <img src="https://i.gifer.com/ZKZg.gif" width="20" height="20"
                                             class="rounded shadow" alt="">
                                    </span>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($sous_souscategories->sortBy('order_column') as $ssc)
                            <tr data-id="{{ $ssc->id }}" class="cusor">
                                <td>{{ $ssc->nom }}</td>
                                <td>{{ $ssc->categorie->nom ?? 'Aucune catégorie' }}</td>
                                <td>{{ $ssc->souscategorie->nom ?? 'Aucune sous-catégorie' }}</td>
                                <td>{{ $ssc->created_at->format('d/m/Y') }}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-dark" type="button"
                                            onclick="OpenModelUpdateSousSousCategorie({{ $ssc->id }})">
                                        <i class="ri-edit-box-line"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger"
                                            onclick="toggle_confirmation({{ $ssc->id }})">
                                        <i class="ri-delete-bin-6-line"></i>
                                    </button>
                                    <button class="btn btn-sm btn-success d-none" type="button"
                                            id="confirmBtn{{ $ssc->id }}"
                                            wire:click="delete({{ $ssc->id }})">
                                        <i class="bi bi-check-circle"></i>
                                        <span class="hide-tablete">Confirmer</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="text-center p-3">
                                        <p>Aucune sous-sous-catégorie trouvée</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card radius-15">
            <div class="card-body">
                <div class="card-title">
                    <h6 class="mb-0 my-auto">
                        Nouvelle sous-sous-catégorie
                    </h6>
                </div>
                <form wire:submit="save">
                    <div class="mb-2">
                        <label for="">Nom</label>
                        <input type="text" name="nom" wire:model="nom" class="form-control">
                        @error('nom')
                        <span class="small text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label for="">Catégorie</label>
                        <select wire:model="id_categorie" class="form-control">
                            <option value="">Sélectionnez une catégorie</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->nom }}</option>
                            @endforeach
                        </select>
                        @error('id_categorie')
                        <span class="small text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label for="">Sous-catégorie</label>
                        <select wire:model="id_souscategorie" class="form-control">
                            <option value="">Sélectionnez une sous-catégorie</option>
                            @foreach ($souscategories as $sc)
                                <option value="{{ $sc->id }}">{{ $sc->nom }}</option>
                            @endforeach
                        </select>
                        @error('id_souscategorie')
                        <span class="small text-danger">{{ $message }}</span>
                        @enderror
                    </div>



                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <span wire:loading>
                                <img src="https://i.gifer.com/ZKZg.gif" height="15" alt="">
                            </span>
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
