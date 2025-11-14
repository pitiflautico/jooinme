<x-app-layout>
    <x-slot name="header">
        <h2 class="mb-0">{{ __('Perfil') }}</h2>
    </x-slot>

    <div class="row g-4">
        <div class="col-lg-8">
            <x-backend.card class="mb-4">
                <livewire:profile.update-profile-information-form />
            </x-backend.card>

            <x-backend.card class="mb-4">
                <livewire:profile.update-password-form />
            </x-backend.card>

            <x-backend.card>
                <livewire:profile.delete-user-form />
            </x-backend.card>
        </div>
    </div>
</x-app-layout>
