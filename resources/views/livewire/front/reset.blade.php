<div>
    <form class="my-3" wire:submit='reset_password'>
        <x-FrontAlert></x-FrontAlert>
        <div class="form-group">
            <label for="email" class="form-label">Mot de passe<span class="text-danger"> * </span></label>
            <input type="password" class="form-control input-h rounded-0" wire:model='password' name="password"
                placeholder="Mot de passe" id="password" required>
            @error('password')
                <span class="text-danger small">
                    {{ $message }}
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="email" class="form-label">Confirmation du mot de passe<span class="text-danger"> * </span></label>
            <input type="password" class="form-control input-h rounded-0" wire:model='password_confirmation' name="password_confirmation"
                placeholder="Confirmation du mot de passe" id="password_confirmation" required>
            @error('password_confirmation')
                <span class="text-danger small">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <button class="btn btn-primary-color w-100 mt-4" type="submit">
            <x-Loading></x-Loading>
            Soumettre
        </button>
    </form>

</div>
