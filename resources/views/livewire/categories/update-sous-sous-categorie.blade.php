<div>
    <div class="card-body">
        <form wire:submit="UpdateSousSousCategorie">
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



            @include('components.alert')

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
