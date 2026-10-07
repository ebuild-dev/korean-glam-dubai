<div>
    <x-FrontAlert></x-FrontAlert>
    <form wire:submit="login" class="mt-3">
        @csrf
        <label for="">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
            wire:model='email' required>
        @error('record')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
        <label for="">Password</label>
        <input type="password" name="password" id="password" class="form-control" wire:model='password' required>
        @error('password')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
        <button type="submit" class="btn btn-primary-color w-100 mt-3">
            <x-Loading></x-Loading>
            Login
        </button>
    </form>

</div>
