<div>
    <div class="card-body">
        <form wire:submit="UpdateCategorie">
            <div class="mb-2">
                <label for="">Nom</label>
                <input type="text" name="nom" wire:model="nom" class="form-control" id="">
                @error('nom')
                    <span class="small text-danger">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <div class="mb-2">
                <label for="">Photo</label>
                <input type="file" name="photo" wire:model="photo" class="form-control" id="">
                @error('photo')
                    <span class="small text-danger">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <div class="mb-2">
                <label for="">Description</label>
                <textarea wire:model="description" class="form-control" rows="5"></textarea>
                @error('description')
                    <span class="small text-danger">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            @include('components.alert')
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                    <span wire:loading>
                        <img src="https://i.gifer.com/ZKZg.gif" height="15" alt="" srcset="">
                    </span>
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
