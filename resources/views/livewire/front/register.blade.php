<div>
    <x-FrontAlert></x-FrontAlert>
    <form class="mt-3" x-data="{
        submitting: false,
        captchaError: '',
        async submitRegistration() {
            if (this.submitting) return;
            this.submitting = true;
            this.captchaError = '';
            try {
                const deadline = Date.now() + 15000;
                while (!window.grecaptcha?.execute) {
                    if (Date.now() >= deadline) throw new Error('captcha unavailable');
                    await new Promise(resolve => setTimeout(resolve, 100));
                }
                const token = await new Promise((resolve, reject) => {
                    const timeout = setTimeout(() => reject(new Error('captcha timeout')), 15000);
                    window.grecaptcha.ready(() => {
                        Promise.resolve().then(() => window.grecaptcha.execute(@js(config('captcha.sitekey')), {action: 'register'}))
                            .then(resolve, reject).finally(() => clearTimeout(timeout));
                    });
                });
                $wire.set('recaptchaToken', token, false);
                await $wire.register();
            } catch (error) {
                this.captchaError = 'Anti-bot verification or connection failed. Please try again.';
            } finally {
                this.submitting = false;
            }
        }
    }" x-on:submit.prevent="submitRegistration()">
        <label for="nom">Full name</label>
        <input type="text" class="form-control" id="nom" name="nom" wire:model='nom' required>
        <label for="email">Email address</label>
        <input type="email" class="form-control" id="email" name="email" wire:model='email' required>
        <label for="phone">Phone number</label>
        <input type="tel" class="form-control" id="phone" name="phone" wire:model='phone' required>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control" wire:model='password' required>
        <div>
            @error('nom')
                <div class="small text-danger"> {{ $message }} </div>
            @enderror
            @error('email')
                <div class="small text-danger"> {{ $message }} </div>
            @enderror
            @error('phone')
                <div class="small text-danger"> {{ $message }} </div>
            @enderror
            @error('password')
                <div class="small text-danger"> {{ $message }} </div>
            @enderror
            @error('recaptchaToken')
                <div class="small text-danger" role="alert"> {{ $message }} </div>
            @enderror
            @error('recaptcha')
                <div class="small text-danger" role="alert"> {{ $message }} </div>
            @enderror
            <div class="small text-danger" role="alert" x-text="captchaError"></div>
        </div>

        <button type="submit" class="btn btn-primary-color w-100 mt-3" x-bind:disabled="submitting">
            <x-Loading></x-Loading>
            Create my account
        </button>
        <script src="https://www.google.com/recaptcha/api.js?hl=en&amp;render={{ config('captcha.sitekey') }}" async defer></script>
    </form>
</div>
