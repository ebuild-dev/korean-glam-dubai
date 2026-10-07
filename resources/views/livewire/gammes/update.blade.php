<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="update">
        <div class="mb-2">
            <label for="">Nom</label>
            <input type="text" wire:model="nom" class="form-control">
            @error('nom') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-2">
            <label for="">Description</label>
            <textarea wire:model="description" class="form-control" rows="4"></textarea>
            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-2">
            <label for="">Ordre d’affichage</label>
            <input type="number" wire:model="order_column" class="form-control">
            @error('order_column') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-2">
            <label for="">Photo actuelle</label><br>
            @if ($photo)
                <img src="{{ Storage::url($photo) }}" alt="" class="rounded mb-2" width="60">
            @else
                <span class="text-muted">Aucune image</span>
            @endif
        </div>

        <div class="mb-2">
            <label for="">Nouvelle photo</label>
            <input type="file" wire:model="new_photo" class="form-control">
            @error('new_photo') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">
                <span wire:loading>
                    <img src="https://i.gifer.com/ZKZg.gif" height="15" alt="">
                </span>
                Mettre à jour
            </button>
        </div>
    </form>
</div>
