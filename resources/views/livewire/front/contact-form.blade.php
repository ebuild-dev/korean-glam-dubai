<div>
    @if (!$end)
        <form wire:submit='save'>
            <div class="form-group">
                <label for="name" class="form-label text-dark">Nom</label>
                <input type="text" class="form-control input-h rounded-0 " name="name" id="name" wire:model='nom'
                    placeholder="Nom">
                @error('nom')
                    <span class="text-danger small">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="email" class="form-label text-dark">Email</label>
                <input type="email" class="form-control input-h rounded-0 " name="email" id="email"
                    wire:model='email' placeholder="Email">
                @error('email')
                    <span class="text-danger small">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="mobile" class="form-label text-dark">Mobile</label>
                <input type="text" class="form-control input-h  rounded-0 mobile-number" name="mobile"
                    id="mobile" wire:model='telephone' placeholder="Mobile">
                @error('telephone')
                    <span class="text-danger small">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="message" class="form-label text-dark">Message</label>
                <textarea class="form-control rounded-0" name="message" id="message" rows="5" wire:model='message'
                    placeholder="Describe your request in detail"></textarea>
                @error('message')
                    <span class="text-danger small">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <button type="submit" class="btn btn-secondary btn-shadow d-block py-3 rounded-0 w-100 mt-3">
                <x-Loading></x-Loading>
                Soumettre
            </button>
        </form>
    @else
        <div class="card p-2">
            <div class="text-center">
                <img src="/icons/icons8-message-100.png" alt="icon" height="80" srcset="">
                <h5 class="text-success">Your message has been sent.</h5>
                <p class="text-muted">We will get back to you as soon as possible.</p>
            </div>
        </div>
    @endif
</div>
