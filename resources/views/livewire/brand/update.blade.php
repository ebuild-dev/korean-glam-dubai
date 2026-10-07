<div>
    <form wire:submit="update">
        <div class="card-body">
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
                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                    </div>
                @enderror
        </div>
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
