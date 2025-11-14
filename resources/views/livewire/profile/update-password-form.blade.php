<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section>
    <header class="mb-4">
        <h4>{{ __('Actualizar Contraseña') }}</h4>
        <p class="text-muted small">
            {{ __('Asegúrate de usar una contraseña larga y aleatoria para mantener tu cuenta segura.') }}
        </p>
    </header>

    <form wire:submit="updatePassword">
        <div class="mb-3">
            <x-backend.input-label for="update_password_current_password" value="{{ __('Contraseña Actual') }}" />
            <x-backend.text-input wire:model="current_password" id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" />
            <x-backend.input-error :messages="$errors->get('current_password')" />
        </div>

        <div class="mb-3">
            <x-backend.input-label for="update_password_password" value="{{ __('Nueva Contraseña') }}" />
            <x-backend.text-input wire:model="password" id="update_password_password" name="password" type="password" autocomplete="new-password" />
            <x-backend.input-error :messages="$errors->get('password')" />
        </div>

        <div class="mb-3">
            <x-backend.input-label for="update_password_password_confirmation" value="{{ __('Confirmar Contraseña') }}" />
            <x-backend.text-input wire:model="password_confirmation" id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
            <x-backend.input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <div class="d-flex align-items-center gap-3">
            <x-backend.button type="submit">
                <i class="ti ti-device-floppy me-1"></i>
                {{ __('Guardar') }}
            </x-backend.button>

            <x-action-message class="text-success small" on="password-updated">
                <i class="ti ti-check me-1"></i>
                {{ __('Guardado.') }}
            </x-action-message>
        </div>
    </form>
</section>
