<div>
    @if ($end)
        <div class="form-control">
            <img width="20" height="20" src="https://img.icons8.com/glyph-neue/20/40C057/sent.png" alt="sent" />
            You have successfully subscribed.
        </div>
    @else
        <form wire:submit='save' class="mt-3">
            <div class="input-group">
                <input type="text" class="form-control border text-dark fw-500 rounded-0 bg-light me-2  @error('email') is-invalid @enderror"
                    name="subscribe_email" placeholder="example@yormailer.com" wire:model='email' required>
                <button class="btn btn-primary subscribe-btn rounded-0" type="submit">
                    <x-Loading></x-Loading>
                    S&#039;abonner
                </button>
            </div>
            @error('email')
                <span class="small text-danger">
                    {{ $message }}
                </span>
            @enderror
        </form>
    @endif
</div>
