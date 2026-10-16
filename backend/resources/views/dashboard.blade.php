<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tableau de bord</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @php
                    $cartes = [
                        ['En attente', $parStatut['en_attente'] ?? 0, 'bg-gray-200 text-gray-800'],
                        ['Affectées', $parStatut['affectee'] ?? 0, 'bg-blue-100 text-blue-800'],
                        ['En cours', $parStatut['en_cours'] ?? 0, 'bg-orange-100 text-orange-800'],
                        ['Livrées', $parStatut['livree'] ?? 0, 'bg-green-100 text-green-800'],
                        ['Livrées aujourd\'hui', $livreesAujourdhui, 'bg-white text-gray-800 border'],
                    ];
                @endphp
                @foreach ($cartes as [$titre, $valeur, $classes])
                    <div class="rounded-lg shadow p-4 {{ $classes }}">
                        <div class="text-3xl font-bold">{{ $valeur }}</div>
                        <div class="text-sm mt-1">{{ $titre }}</div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white shadow rounded overflow-hidden">
                <h3 class="px-4 py-3 font-semibold border-b">Top chauffeurs (livraisons terminées)</h3>
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">Chauffeur</th>
                            <th class="px-4 py-2">Livrées</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topChauffeurs as $i => $chauffeur)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $i + 1 }}</td>
                                <td class="px-4 py-2">{{ $chauffeur->nom }}</td>
                                <td class="px-4 py-2">{{ $chauffeur->livrees_count }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-6 text-center text-gray-500">Aucun chauffeur.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>