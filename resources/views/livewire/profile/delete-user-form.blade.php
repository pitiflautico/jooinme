<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section>
    <header class="mb-4">
        <h4>{{ __('Eliminar Cuenta') }}</h4>
        <p class="text-muted small">
            {{ __('Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente. Antes de eliminar tu cuenta, descarga cualquier dato o información que desees conservar.') }}
        </p>
    </header>

    <x-backend.button
        variant="danger"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >
        <i class="ti ti-trash me-1"></i>
        {{ __('Eliminar Cuenta') }}
    </x-backend.button>

    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-4">

            <h5 class="mb-3">
                {{ __('¿Estás seguro de que quieres eliminar tu cuenta?') }}
            </h5>

            <p class="text-muted mb-4">
                {{ __('Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente. Por favor, introduce tu contraseña para confirmar que deseas eliminar permanentemente tu cuenta.') }}
            </p>

            <div class="mb-4">
                <x-backend.input-label for="password" value="{{ __('Contraseña') }}" class="visually-hidden" />
                <x-backend.text-input
                    wire:model="password"
                    id="password"
                    name="password"
                    type="password"
                    placeholder="{{ __('Contraseña') }}"
                />
                <x-backend.input-error :messages="$errors->get('password')" />
            </div>

            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary" x-on:click="$dispatch('close')">
                    {{ __('Cancelar') }}
                </button>

                <x-backend.button variant="danger" type="submit">
                    <i class="ti ti-trash me-1"></i>
                    {{ __('Eliminar Cuenta') }}
                </x-backend.button>
            </div>
        </form>
    </x-modal>
</section>
