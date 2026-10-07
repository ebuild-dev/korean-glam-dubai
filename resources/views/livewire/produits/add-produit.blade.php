<div>

    @include('components.alert')

    @if ($produit)
        <form wire:submit="update_produit">
        @else
            <form wire:submit="create">
    @endif

    <div class="row">
        <div class="col-sm-8">
            <div class="row">
                <div class="col-sm-12 mb-3">
                    <label for="">Nom du produit</label>
                    <input type="text" name="nom" class="form-control" wire:model="nom">
                    @error('nom')
                        <span class="text-danger small"> {{ $message }} </span>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="">Description du produit</label>
                <textarea name="description" wire:model="description" class="form-control" rows="10"></textarea>
                @error('description')
                    <span class="text-danger small"> {{ $message }} </span>
                @enderror
            </div>
            <div class="mb-3">
                <div class="form-control">
                    <input type="checkbox" name="best_sellers" wire:model="best_sellers"  @checked($best_sellers)>
                Marquer comme produit best seller
                </div>
            </div>
            <div class="mb-3">
                <div class="form-control">
                    <input type="checkbox" wire:model="trend">
                    Marquer comme produit tendance
                </div>
            </div>

            <div class="mb-3">
                <div class="form-control">
                    <input type="checkbox"
                           name="masquer_produit"
                           wire:model="masquer_produit"
                        @checked($masquer_produit)>
                    Masquer ce produit (ne pas l’afficher sur le site)
                </div>
            </div>
            <div class="mb-3">
                <div class="form-control">
                    <input type="checkbox"
                           name="pris_order"
                           wire:model="pris_order"
                        @checked($pris_order)>
                    Pre-order ce produit
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="">Prix de vente</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    TN
                                </span>
                            </div>
                            <input type="number" step="0.1" name="prix" class="form-control" wire:model="prix">
                        </div>
                        @error('prix')
                            <span class="text-danger small"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="">Référence du produit</label>
                        <input type="text" step="0.1" name="reference" class="form-control"
                            wire:model="reference">
                        @error('reference')
                            <span class="text-danger small"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="">Categorie</label>
                        <select wire:model="id_categorie" class="form-control">
                            <option value=""></option>
                            @foreach ($categories->sortBy('order_column') as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->nom }}
                                </option>
                            @endforeach
                        </select>
                        <div class="small d-flex justify-content-end">
                            <a href="{{ route('categories') }}">
                                Ajouter une catégorie
                            </a>
                        </div>
                        @error('id_categorie')
                            <span class="text-danger small"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="">Sous-categorie</label>
                        <select wire:model="id_sous_categorie" class="form-control">
                            <option value=""></option>
                            @foreach ($sous_categories->sortBy('order_column') as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->nom }}
                                </option>
                            @endforeach
                        </select>
                        <div class="small d-flex justify-content-end">
                            <a href="{{ route('souscategories') }}">
                                Ajouter une sous-catégorie
                            </a>
                        </div>
                        @error('id_sous_categorie')
                            <span class="text-danger small"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="mb-3"     wire:ignore>  {{-- ✅ isole complètement Tagify du DOM Livewire --}}
                        <label for="sousSousCategorieTagify">Sous-sous-catégories</label>
                        <input id="sousSousCategorieTagify"
                               class="form-control"
                               type="text"

                               name="sousSousCategorieTagify"
                               placeholder="Rechercher..."
                               autocomplete="off">
                        <div class="small d-flex justify-content-end">
                            <a href="{{ route('soussouscategories') }}">Ajouter une sous-sous-catégorie</a>
                        </div>
                    </div>

                    {{-- ❌ pas dans le bloc wire:ignore sinon le message disparaît --}}
                    @error('sous_souscategories_ids.*')
                    <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>







                <div class="col-sm-4">
                    <div class="mb-3">
                        <label for="">Marque</label>
                        <select wire:model="id_brand" class="form-control">
                            <option value=""></option>
                            @foreach ($marques as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->brandname }}
                                </option>
                            @endforeach
                        </select>
                        <div class="small d-flex justify-content-end">
                            <a href="{{ route('marques') }}">
                                Ajouter une marque
                            </a>
                        </div>
                        @error('id_brand')
                            <span class="text-danger small"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="mb-3">
                        <label for="">Gamme</label>
                        <select wire:model="id_gamme" class="form-control">
                            <option value=""></option>
                            @foreach ($gammes->sortBy('order_column') as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->nom }}
                                </option>
                            @endforeach
                        </select>

                        <div class="small d-flex justify-content-end">
                            <a href="{{ route('gammes.index') }}">
                                Ajouter une gamme
                            </a>
                        </div>

                        @error('id_gamme')
                        <span class="text-danger small"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="mb-3">
                        <label for="">Points d'achat</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                <span class="input-group-text">
                    PTS
                </span>
                            </div>
                            <input type="number" step="1" name="points_achat" class="form-control" wire:model="points_achat">
                        </div>
                        @error('points_achat')
                        <span class="text-danger small"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="mb-3">
                        <label for="">Points de vente</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                <span class="input-group-text">
                    PTS
                </span>
                            </div>
                            <input type="number" step="1" name="points_vente" class="form-control" wire:model="points_vente">
                        </div>
                        @error('points_vente')
                        <span class="text-danger small"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="mb-3">
                        <label for="">Stock (facultatif)</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Qté</span>
                            </div>
                            <input type="number" step="1" name="stock" class="form-control" wire:model="stock" placeholder="Ex : 50">
                        </div>
                        @error('stock')
                        <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


            </div>
        </div>
        <div class="col-sm-4">
            <div class="mb-3">
                <label for="">Photo d'illustration</label>
                <p class="small text-muted mb-2">Taille maximale : 50 Mo par image.</p>
                <p class="small text-muted mb-2">
                    Dimensions conseillées : <strong>201 × 300 px</strong>
                </p>
                <div class="preview-produit-illustration" onclick="preview_illustration('new-prosduit')">
                    @if ($photo && $photo->isPreviewable())
                        <img src="{{ $photo->temporaryUrl() }}" alt="Photo sélectionnée" class="w-100">
                    @else
                        <img src="{{ $photo2 ?: asset('def.jpg') }}" alt="Photo du produit" class="w-100">
                    @endif
                </div>
                <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" class=" d-none" id="file-input-new-prosduit"
                    wire:model="photo">
                @error('photo')
                    <span class="text-danger small"> {{ $message }} </span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="product-other-photos">Autres photos</label>
                <p class="small text-muted mb-2">Taille maximale : 50 Mo par image.</p>
                <p id="product-photos-help" class="small text-muted mb-2">
                    Dimensions conseillées : <strong>201 × 300 px</strong>

                @if ($produit)
                      <br> Les nouvelles photos seront ajoutées à la galerie. Les suppressions seront appliquées avec « Mettre à jour ».
                    @endif
                </p>
                <div x-data="{ dragging: false, uploading: false, progress: 0, failed: false }"
                    x-on:livewire-upload-start="uploading = true; progress = 0; failed = false"
                    x-on:livewire-upload-finish="uploading = false; $refs.photos.value = ''"
                    x-on:livewire-upload-error="uploading = false; failed = true"
                    x-on:livewire-upload-cancel="uploading = false"
                    x-on:livewire-upload-progress="progress = $event.detail.progress">
                    <div class="product-photos-dropzone rounded p-4 text-center"
                        :class="{ 'is-dragging': dragging }">
                        <input id="product-other-photos" x-ref="photos" type="file" multiple name="photos"
                            accept="image/jpeg,image/png,image/webp" wire:model="photos"
                            aria-describedby="product-photos-help" aria-label="Ajouter les autres photos"
                            :disabled="uploading"
                            x-on:dragenter="dragging = true" x-on:dragover="dragging = true"
                            x-on:dragleave="dragging = false" x-on:drop="dragging = false"
                            x-on:change="dragging = false">
                        <strong class="d-block">Glissez-déposez vos photos ici</strong>
                        <span class="small text-muted">ou cliquez pour choisir plusieurs images</span>
                    </div>
                    <div x-show="uploading" style="display: none" class="mt-2" role="status" aria-live="polite">
                        <span class="small">Téléversement : <span x-text="progress">0</span> %</span>
                        <progress class="w-100" max="100" :value="progress"></progress>
                    </div>
                    <p x-show="failed" style="display: none" class="text-danger small mt-2" role="alert">
                        Le téléversement a échoué. Veuillez réessayer.
                    </p>
                </div>
                @error('photos')
                    <span class="text-danger small"> {{ $message }} </span>
                @enderror
                @error('photos.*')
                    <span class="text-danger small"> {{ $message }} </span>
                @enderror
                <div class="d-flex flex-wrap gap-2 mt-2">
                    @foreach (json_decode($photos2 ?: '[]', true) ?: [] as $index => $galleryPhoto)
                        @if (!in_array($galleryPhoto, $removedGalleryPhotos, true))
                            <div wire:key="gallery-existing-{{ $index }}" class="product-gallery-preview">
                                <img src="{{ \App\Models\produits::imageUrl($galleryPhoto) }}" alt="Photo actuelle {{ $index + 1 }}">
                                <button type="button" class="btn btn-outline-danger btn-sm w-100 mt-1"
                                    wire:click="removeExistingPhoto({{ $index }})" wire:loading.attr="disabled"
                                    aria-label="Supprimer la photo actuelle {{ $index + 1 }}">Supprimer</button>
                            </div>
                        @endif
                    @endforeach
                    @foreach ($photos ?: [] as $index => $galleryPhoto)
                        <div wire:key="gallery-upload-{{ $galleryPhoto->getFilename() }}" class="product-gallery-preview">
                            @if ($galleryPhoto->isPreviewable())
                                <img src="{{ $galleryPhoto->temporaryUrl() }}" alt="Nouvelle photo {{ $index + 1 }}">
                            @endif
                            <span class="small d-block text-truncate" title="{{ $galleryPhoto->getClientOriginalName() }}">{{ $galleryPhoto->getClientOriginalName() }}</span>
                            <button type="button" class="btn btn-outline-danger btn-sm w-100 mt-1"
                                wire:click="removeNewPhoto({{ $index }})" wire:loading.attr="disabled"
                                aria-label="Retirer la nouvelle photo {{ $index + 1 }}">Retirer</button>
                        </div>
                    @endforeach
                </div>
            </div>


        </div>
    </div>
    <div style="text-align: right;" class="mt-5">
        <button class="btn btn-primary btn-sm px-5" type="submit" wire:loading.attr="disabled">
            <span wire:loading>
                <img src="https://i.gifer.com/ZKZg.gif" height="15" alt="" srcset="">
            </span>
            @if ($produit)
                Mettre a jour
            @else
                Enregistrer le produit
            @endif
        </button>
    </div>
    </form>

    <style>
        .preview-produit-illustration{
            height: 350px;
            cursor: pointer;
        }
        .preview-produit-illustration img { width: 100%; height: 100%; object-fit: contain; }
        .product-photos-dropzone { position: relative; border: 2px dashed #8795a5; background: #f8f9fa; }
        .product-photos-dropzone.is-dragging,
        .product-photos-dropzone:focus-within { border-color: #0d6efd; background: #eaf2ff; }
        .product-photos-dropzone input { position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
        .product-photos-dropzone input:disabled { cursor: wait; }
        .product-gallery-preview { width: 90px; }
        .product-gallery-preview img { width: 90px; height: 90px; object-fit: contain; border: 1px solid #dee2e6; border-radius: 6px; }
    </style>

</div>
