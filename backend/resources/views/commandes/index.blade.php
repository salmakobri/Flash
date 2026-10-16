<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Commandes</h2>
            <a href="{{ route('commandes.create') }}"
               class="px-4 py-2 bg-gray-800 text-white rounded text-sm">Nouvelle commande</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            <form method="GET" class="mb-4 bg-white shadow rounded p-4 flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Statut</label>
                    <select name="statut" class="border-gray-300 rounded text-sm">
                        <option value="">Tous</option>
                        @foreach (['en_attente', 'affectee', 'en_cours', 'livree'] as $s)
                            <option value="{{ $s }}" @selected(request('statut') === $s)>
                                {{ str_replace('_', ' ', $s) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Chauffeur</label>
                    <select name="chauffeur_id" class="border-gray-300 rounded text-sm">
                        <option value="">Tous</option>
                        @foreach ($chauffeurs as $c)
                            <option value="{{ $c->id }}" @selected(request('chauffeur_id') == $c->id)>
                                {{ $c->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Date</label>
                    <input type="date" name="date" value="{{ request('date') }}"
                           class="border-gray-300 rounded text-sm">
                </div>
                <button class="px-4 py-2 bg-gray-800 text-white rounded text-sm">Filtrer</button>
                <a href="{{ route('commandes.index') }}" class="px-4 py-2 border rounded text-sm">Réinitialiser</a>
            </form>

            <div class="bg-white shadow rounded overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Client</th>
                            <th class="px-4 py-3">Adresse</th>
                            <th class="px-4 py-3">Chauffeur</th>
                            <th class="px-4 py-3">Statut</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($commandes as $commande)
                            <tr class="border-t">
                                <td class="px-4 py-3">{{ $commande->id }}</td>
                                <td class="px-4 py-3">{{ $commande->date->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">{{ $commande->client->nom }}</td>
                                <td class="px-4 py-3">{{ $commande->adresse_livraison }}</td>
                                <td class="px-4 py-3">{{ $commande->chauffeur->nom ?? '—' }}</td>
                                <td class="px-4 py-3"><x-statut-badge :statut="$commande->statut" /></td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <a href="{{ route('commandes.show', $commande) }}" class="text-blue-600 mr-3">Voir</a>
                                    <a href="{{ route('commandes.edit', $commande) }}" class="text-blue-600 mr-3">Modifier</a>
                                    <form method="POST" action="{{ route('commandes.destroy', $commande) }}"
                                          class="inline"
                                          onsubmit="return confirm('Supprimer cette commande ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">Aucune commande.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $commandes->links() }}</div>
        </div>
    </div>
</x-app-layout>