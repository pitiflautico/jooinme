<x-guest-layout>
    <h2 class="auth-title">Welcome Back</h2>
    <p class="auth-subtitle">Sign in to your account to continue</p>

    <!-- Session Status -->
    @if(session('status'))
    <x-backend.alert type="success" class="mb-3">
        {{ session('status') }}
    </x-backend.alert>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <x-backend.input-label for="email" :value="__('Email Address')" />
            <x-backend.text-input
                id="email"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
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
                autocomplete="current-password"
                placeholder="Enter your password" />
            <x-backend.input-error :messages="$errors->get('password')" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="auth-options">
            <div class="form-check">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label">
                    {{ __('Remember me') }}
                </label>
            </div>

            @if (Route::has('password.request'))
            <a class="auth-link" href="{{ route('password.request') }}">
                {{ __('Forgot password?') }}
            </a>
            @endif
        </div>

        <!-- Submit Button -->
        <x-backend.button type="submit" variant="primary" class="btn-auth">
            <i class="ti ti-login me-2"></i>
            {{ __('Sign In') }}
        </x-backend.button>

        <!-- Register Link -->
        @if (Route::has('register'))
        <div class="text-center mt-3">
            <span class="text-muted">Don't have an account?</span>
            <a class="auth-link ms-1" href="{{ route('register') }}">
                {{ __('Sign up') }}
            </a>
        </div>
        @endif
    </form>
</x-guest-layout>
