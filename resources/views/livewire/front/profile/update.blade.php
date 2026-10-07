<div>
    <form wire:submit='update' enctype="multipart/form-data">
        <x-FrontAlert></x-FrontAlert>
        <div class="row">
            <div class="col-md-4">
                <input type="hidden" value="1271" name="id">
                <label for="name" class="label-style my-2">Nom : <span
                        class="required text-danger">*</span></label>
                <input type="text" class="form-control input-h rounded-0" name="name" wire:model='nom'
                    placeholder="Nom" >
                    @error('nom')
                    <span class="text-danger small">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label for="email" class="label-style my-2">Email : <span
                        class="required text-danger">*</span></label>
                <input type="text" class="form-control input-h rounded-0" name="email" wire:model='email'
                    placeholder="Email">
                    @error('email')
                    <span class="text-danger small">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label for="mobile" class="label-style my-2">Mobile : <span
                        class="required text-danger">*</span></label>
                <input type="text" class="form-control input-h rounded-0" name="mobile" wire:model='telephone'
                    placeholder="Mobile" >
                    @error('telephone')
                    <span class="text-danger small">
                        {{ $message }}
                    </span>
                @enderror
            </div>
        </div>
        <div class="d-flex justify-content-end">
            <button class="btn btn-fashion mt-3" type="submit">
                <x-Loading></x-Loading>
                Soumettre
            </button>
        </div>
    </form>
</div>
