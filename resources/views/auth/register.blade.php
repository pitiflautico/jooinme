<x-guest-layout>
    <h2 class="auth-title">Create Account</h2>
    <p class="auth-subtitle">Join us today and start connecting</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <x-backend.input-label for="name" :value="__('Full Name')" />
            <x-backend.text-input
                id="name"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
                placeholder="Enter your full name" />
            <x-backend.input-error :messages="$errors->get('name')" />
        </div>

        <!-- Email Address -->
        <div class="form-group">
            <x-backend.input-label for="email" :value="__('Email Address')" />
            <x-backend.text-input
                id="email"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="username"
                placeholder="Enter your email" />
            <x-backend.input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div class="form-group">
            <x-backend.input-label for="password" :value="__('Password')" />
            <x-backend.text-input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="Create a password" />
            <x-backend.input-error :messages="$errors->get('password')" />
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <x-backend.input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-backend.text-input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Confirm your password" />
            <x-backend.input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <!-- Submit Button -->
        <x-backend.button type="submit" variant="primary" class="btn-auth">
            <i class="ti ti-user-plus me-2"></i>
            {{ __('Create Account') }}
        </x-backend.button>

        <!-- Login Link -->
        <div class="text-center mt-3">
            <span class="text-muted">Already have an account?</span>
            <a class="auth-link ms-1" href="{{ route('login') }}">
                {{ __('Sign in') }}
            </a>
        </div>
    </form>
</x-guest-layout>
