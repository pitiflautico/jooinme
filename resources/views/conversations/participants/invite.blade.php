<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Invitar Participantes: {{ $conversation->title }}
            </h2>
            <a href="{{ route('conversations.participants.index', $conversation) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition text-sm">
                ← Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Invitar participantes</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Puedes invitar personas por email o seleccionar usuarios registrados.
                    </p>
                </div>

                <!-- Tab Navigation -->
                <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                    <nav class="-mb-px flex gap-4">
                        <button onclick="showTab('users')" id="tab-users" class="tab-button py-2 px-1 border-b-2 border-indigo-600 text-indigo-600 dark:text-indigo-400 font-medium text-sm">
                            Usuarios Registrados
                        </button>
                        <button onclick="showTab('email')" id="tab-email" class="tab-button py-2 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 font-medium text-sm">
                            Por Email
                        </button>
                    </nav>
                </div>

                <!-- Invite by User Selection -->
                <form id="form-users" action="{{ route('conversations.participants.invite', $conversation) }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="type" value="user">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            Selecciona usuarios para invitar
                        </label>

                        @if($availableUsers->count() > 0)
                            <div class="max-h-96 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                                @foreach($availableUsers as $user)
                                    <label class="flex items-center p-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-200 dark:border-gray-700 last:border-0">
                                        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mr-3">
                                        <div class="flex items-center gap-3 flex-1">
                                            <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <div class="mt-4">
                                <label for="message_users" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Mensaje personalizado (opcional)
                                </label>
                                <textarea id="message_users" name="message" rows="3" placeholder="Escribe un mensaje de invitación..." class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"></textarea>
                            </div>

                            <div class="flex justify-end gap-3">
                                <a href="{{ route('conversations.participants.index', $conversation) }}" class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                    Cancelar
                                </a>
                                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                                    Enviar invitaciones
                                </button>
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <p class="text-gray-600 dark:text-gray-400">No hay usuarios disponibles para invitar.</p>
                                <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Todos los usuarios registrados ya están en esta conversación.</p>
                            </div>
                        @endif
                    </div>
                </form>

                <!-- Invite by Email -->
                <form id="form-email" action="{{ route('conversations.participants.invite', $conversation) }}" method="POST" class="space-y-6 hidden">
                    @csrf
                    <input type="hidden" name="type" value="email">

                    <div>
                        <label for="emails" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Direcciones de email
                            <span class="text-xs text-gray-500">(Una por línea)</span>
                        </label>
                        <textarea id="emails_input" rows="6" placeholder="email1@example.com&#10;email2@example.com&#10;email3@example.com" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"></textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Ingresa cada email en una línea separada
                        </p>
                    </div>

                    <div>
                        <label for="message_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Mensaje personalizado (opcional)
                        </label>
                        <textarea id="message_email" name="message" rows="3" placeholder="Escribe un mensaje de invitación..." class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"></textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('conversations.participants.index', $conversation) }}" class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-6 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                            Enviar invitaciones
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showTab(tab) {
            // Hide all forms
            document.getElementById('form-users').classList.add('hidden');
            document.getElementById('form-email').classList.add('hidden');

            // Remove active state from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-indigo-600', 'text-indigo-600', 'dark:text-indigo-400');
                button.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            });

            // Show selected form and activate tab
            if (tab === 'users') {
                document.getElementById('form-users').classList.remove('hidden');
                document.getElementById('tab-users').classList.add('border-indigo-600', 'text-indigo-600', 'dark:text-indigo-400');
                document.getElementById('tab-users').classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            } else {
                document.getElementById('form-email').classList.remove('hidden');
                document.getElementById('tab-email').classList.add('border-indigo-600', 'text-indigo-600', 'dark:text-indigo-400');
                document.getElementById('tab-email').classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            }
        }

        // Parse emails from textarea before submit
        document.getElementById('form-email').addEventListener('submit', function(e) {
            const textarea = document.getElementById('emails_input');
            const emails = textarea.value.split('\n').map(email => email.trim()).filter(email => email.length > 0);

            if (emails.length === 0) {
                e.preventDefault();
                alert('Por favor, ingresa al menos un email.');
                return;
            }

            // Create hidden inputs for each email
            emails.forEach(email => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'emails[]';
                input.value = email;
                this.appendChild(input);
            });
        });
    </script>
</x-app-layout>
