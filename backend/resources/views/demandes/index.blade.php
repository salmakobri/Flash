<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Demandes d'inscription</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            <form method="GET" class="mb-4 bg-white shadow rounded p-4 flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Type</label>
                    <select name="type" class="border-gray-300 rounded text-sm">
                        <option value="">Tous</option>
                        <option value="rider" @selected(request('type') === 'rider')>Livreurs</option>
                        <option value="partner" @selected(request('type') === 'partner')>Magasins</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Statut</label>
                    <select name="statut" class="border-gray-300 rounded text-sm">
                        <option value="">Tous</option>
                        <option value="en_attente" @selected(request('statut') === 'en_attente')>En attente</option>
                        <option value="acceptee" @selected(request('statut') === 'acceptee')>Acceptées</option>
                        <option value="refusee" @selected(request('statut') === 'refusee')>Refusées</option>
                    </select>
                </div>
                <button class="px-4 py-2 bg-gray-800 text-white rounded text-sm">Filtrer</button>
                <a href="{{ route('demandes.index') }}" class="px-4 py-2 border rounded text-sm">Réinitialiser</a>
            </form>

            @php
                $badges = [
                    'en_attente' => 'bg-orange-100 text-orange-800',
                    'acceptee' => 'bg-green-100 text-green-800',
                    'refusee' => 'bg-red-100 text-red-800',
                ];
                $labels = ['en_attente' => 'en attente', 'acceptee' => 'acceptée', 'refusee' => 'refusée'];
            @endphp

            <div class="bg-white shadow rounded overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Nom</th>
                            <th class="px-4 py-3">Contact</th>
                            <th class="px-4 py-3">Détail</th>
                            <th class="px-4 py-3">Statut</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($demandes as $d)
                            <tr class="border-t">
                                <td class="px-4 py-3">{{ $d->created_at->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">{{ $d->type === 'rider' ? 'Livreur' : 'Magasin' }}</td>
                                <td class="px-4 py-3">{{ $d->nom }}</td>
                                <td class="px-4 py-3">{{ $d->email }}<br><span class="text-gray-500">{{ $d->telephone }}</span></td>
                                <td class="px-4 py-3">
                                    @if ($d->type === 'rider')
                                        {{ ucfirst($d->vehicule) }}, {{ $d->ville }}
                                    @else
                                        {{ $d->magasin }} ({{ $d->categorie }}), {{ $d->ville }}
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded text-xs {{ $badges[$d->statut] ?? '' }}">
                                        {{ $labels[$d->statut] ?? $d->statut }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('demandes.show', $d) }}" class="text-blue-600">Voir</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">Aucune demande.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $demandes->links() }}</div>
        </div>
    </div>
</x-app-layout>