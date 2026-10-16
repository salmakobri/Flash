<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Chauffeurs</h2>
            <a href="{{ route('chauffeurs.create') }}"
               class="px-4 py-2 bg-gray-800 text-white rounded text-sm">Nouveau chauffeur</a>
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
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Commandes</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($chauffeurs as $chauffeur)
                            <tr class="border-t">
                                <td class="px-4 py-3">{{ $chauffeur->nom }}</td>
                                <td class="px-4 py-3">{{ $chauffeur->user->email }}</td>
                                <td class="px-4 py-3">{{ $chauffeur->commandes_count }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('chauffeurs.edit', $chauffeur) }}"
                                       class="text-blue-600 mr-3">Modifier</a>
                                    <form method="POST" action="{{ route('chauffeurs.destroy', $chauffeur) }}"
                                          class="inline"
                                          onsubmit="return confirm('Supprimer ce chauffeur ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">Aucun chauffeur.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $chauffeurs->links() }}</div>
        </div>
    </div>
</x-app-layout>