<div class="row">
    <div class="col-sm-8">
        <div class="card radius-15">
            <div class="card-body">
                <div class="card-title">
                    <h5 class="mb-0 my-auto">
                        Liste des gammes
                    </h5>
                </div>

                <div class="table-responsive-sm">
                    <table id="basic-datatable" class="table sortable-list table-striped dt-responsive nowrap w-100">
                        <thead class="table-dark cursor">
                        <tr>
                            <th>Photo</th>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Produits</th>
                            <th>Création</th>
                            <th style="text-align: right;">
                                    <span wire:loading>
                                        <img src="https://i.gifer.com/ZKZg.gif" width="20" height="20" class="rounded shadow" alt="">
                                    </span>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($gammes->sortBy('order_column') as $gamme)
                            <tr data-id="{{ $gamme->id }}" class="cursor">
                                <td>
                                    @if ($gamme->photo ?? false)
                                        <img src="{{ Storage::url($gamme->photo) }}" class="img-fluid rounded-circle" alt="Image" height="30" width="30">
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td>{{ $gamme->nom }}</td>
                                <td>{{ Str::limit($gamme->description, 60) }}</td>
                                <td>{{ $gamme->produits->count() }}</td>
                                <td>{{ $gamme->created_at->format('d/m/Y') }}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-dark" type="button"
                                            onclick="OpenModelUpdateGamme({{ $gamme->id }})">
                                        <i class="ri-edit-box-line"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger"
                                            onclick="toggle_confirmation({{ $gamme->id }})">
                                        <i class="ri-delete-bin-6-line"></i>
                                    </button>
                                    <button class="btn btn-sm btn-success d-none" type="button"
                                            id="confirmBtn{{ $gamme->id }}"
                                            wire:click="delete({{ $gamme->id }})">
                                        <i class="bi bi-check-circle"></i>
                                        <span class="hide-tablete">
                                                Confirmer
                                            </span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="text-center p-3">
                                        <p>Aucune gamme trouvée</p>
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
                        Nouvelle gamme
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
                        <label for="">Photo</label>
                        <input type="file" name="photo" wire:model="photo" class="form-control">
                        @error('photo')
                        <span class="small text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label for="">Description</label>
                        <textarea wire:model="description" class="form-control" rows="5"></textarea>
                        @error('description')
                        <span class="small text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label for="">Ordre d’affichage</label>
                        <input type="number" name="order_column" wire:model="order_column" class="form-control">
                        @error('order_column')
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
