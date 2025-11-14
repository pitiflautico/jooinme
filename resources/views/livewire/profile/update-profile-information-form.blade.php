<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header class="mb-4">
        <h4>{{ __('Información del Perfil') }}</h4>
        <p class="text-muted small">
            {{ __("Actualiza la información de tu cuenta y dirección de correo electrónico.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation">
        <div class="mb-3">
            <x-backend.input-label for="name" value="{{ __('Nombre') }}" />
            <x-backend.text-input wire:model="name" id="name" name="name" type="text" required autofocus autocomplete="name" />
            <x-backend.input-error :messages="$errors->get('name')" />
        </div>

        <div class="mb-3">
            <x-backend.input-label for="email" value="{{ __('Email') }}" />
            <x-backend.text-input wire:model="email" id="email" name="email" type="email" required autocomplete="username" />
            <x-backend.input-error :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div class="mt-2">
                    <x-backend.alert type="warning" :dismissible="false">
                        {{ __('Tu dirección de correo no está verificada.') }}
                        <button wire:click.prevent="sendVerification" class="btn btn-link p-0 text-decoration-underline">
                            {{ __('Haz clic aquí para reenviar el correo de verificación.') }}
                        </button>
                    </x-backend.alert>

                    @if (session('status') === 'verification-link-sent')
                        <x-backend.alert type="success" class="mt-2">
                            {{ __('Se ha enviado un nuevo enlace de verificación a tu correo.') }}
                        </x-backend.alert>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <x-backend.button type="submit">
                <i class="ti ti-device-floppy me-1"></i>
                {{ __('Guardar') }}
            </x-backend.button>

            <x-action-message class="text-success small" on="profile-updated">
                <i class="ti ti-check me-1"></i>
                {{ __('Guardado.') }}
            </x-action-message>
        </div>
    </form>
</section>
