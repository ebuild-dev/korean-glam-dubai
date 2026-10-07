<div>
    <x-FrontAlert></x-FrontAlert>
    <form wire:submit='update'>
        <div class="row">
            <div class="col-md-4 mb-4">
                <label class="form-label" class="label-style my-4">Mot de passe actuel : <span class="required text-danger">*</span></label>
                <input type="password" wire:model="current_password" class="form-control input-h form-control-md rounded-0" placeholder="Mot de passe actuel" required="">
                @error('current_password')
                    <span class="text-danger small">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <div class="col-md-4 mb-4">
                <label class="form-label" class="label-style my-4">Nouveau mot de passe : <span class="required text-danger">*</span></label>
                <input type="password" wire:model="password" class="form-control input-h form-control-md rounded-0 mb-0" placeholder="Nouveau mot de passe" required="">
                @error('password')
                    <span class="text-danger small">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <div class="col-md-4 mb-4">
                <label class="form-label" class="label-style my-4">Confirmer le mot de passe : <span class="required text-danger">*</span></label>
                <input type="password" wire:model="password_confirmation" class="form-control input-h form-control-md rounded-0 mb-0" placeholder="Confirmer le mot de passe" required="">
                @error('password_confirmation')
                    <span class="text-danger small">
                        {{ $message }}
                    </span>
                @enderror
        </div>
        <div class="d-flex justify-content-end">
            <button class="btn btn-fashion mt-3">
                <x-Loading></x-Loading>
                Sauvegarder
            </button>
        </div>
    </form>
</div>
