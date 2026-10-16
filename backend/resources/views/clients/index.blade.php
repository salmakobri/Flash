<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Clients</h2>
            <a href="{{ route('clients.create') }}"
               class="px-4 py-2 bg-gray-800 text-white rounded text-sm">Nouveau client</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow rounded overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Nom</th>
                            <th class="px-4 py-3">Téléphone</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clients as $client)
                            <tr class="border-t">
                                <td class="px-4 py-3">{{ $client->nom }}</td>
                                <td class="px-4 py-3">{{ $client->telephone }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('clients.edit', $client) }}"
                                       class="text-blue-600 mr-3">Modifier</a>
                                    <form method="POST" action="{{ route('clients.destroy', $client) }}"
                                          class="inline"
                                          onsubmit="return confirm('Supprimer ce client ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-6 text-center text-gray-500">Aucun client.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $clients->links() }}</div>
        </div>
    </div>
</x-app-layout>