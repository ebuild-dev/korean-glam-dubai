<div>
    @if (!$end)
        <form class="my-3" wire:submit='forget'>
            <x-FrontAlert></x-FrontAlert>
            <div class="form-group">
                <input type="email" class="form-control input-h rounded-0" wire:model='email' name="email"
                    placeholder="Email" id="email" required>
                @error('email')
                    <span class="text-danger small">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <button class="btn btn-primary-color w-100 mt-4" type="submit">
                <x-Loading></x-Loading>
                Get new password
            </button>
        </form>
    @else
        <div class="card p-2">
            <div class="text-center">
                <img src="/icons/icons8-verrouiller-2-64.png" alt="icon" height="80" srcset="">
                <h5 class="text-success">
                    Your password reset request has been sent.
                </h5>
                <p class="text-muted">
                    Please check your inbox or spam folder for the
                    password reset instructions.
                </p>
            </div>
        </div>
    @endif
</div>
