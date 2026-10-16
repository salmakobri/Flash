<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Commande #{{ $commande->id }}</h2>
            <a href="{{ route('commandes.index') }}" class="text-sm text-blue-600">&larr; Retour à la liste</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="p-3 bg-red-100 text-red-800 rounded">
                    @foreach ($errors->all() as $error) <div>{{ $error }}</div> @endforeach
                </div>
            @endif

            <div class="bg-white shadow rounded p-6">
                <dl class="grid grid-cols-3 gap-y-3 text-sm">
                    <dt class="text-gray-500">Statut</dt>
                    <dd class="col-span-2"><x-statut-badge :statut="$commande->statut" /></dd>

                    <dt class="text-gray-500">Client</dt>
                    <dd class="col-span-2">{{ $commande->client->nom }} ({{ $commande->client->telephone }})</dd>

                    <dt class="text-gray-500">Date</dt>
                    <dd class="col-span-2">{{ $commande->date->format('d/m/Y') }}</dd>

                    <dt class="text-gray-500">Adresse</dt>
                    <dd class="col-span-2">{{ $commande->adresse_livraison }}</dd>

                    <dt class="text-gray-500">Détails</dt>
                    <dd class="col-span-2">{!! nl2br(e($commande->details ?? '—')) !!}</dd>

                    <dt class="text-gray-500">Chauffeur</dt>
                    <dd class="col-span-2">{{ $commande->chauffeur->nom ?? 'Non affecté' }}</dd>

                    <dt class="text-gray-500">Note du chauffeur</dt>
                    <dd class="col-span-2">{{ $commande->note ?? '—' }}</dd>
                </dl>
            </div>

            @if (in_array($commande->statut, ['en_attente', 'affectee']))
                <div class="bg-white shadow rounded p-6">
                    <h3 class="font-semibold mb-3">Affecter un chauffeur</h3>
                    <form method="POST" action="{{ route('commandes.affecter', $commande) }}"
                          class="flex gap-3 items-center">
                        @csrf
                        <select name="chauffeur_id" class="border-gray-300 rounded text-sm">
                            <option value="">-- Choisir --</option>
                            @foreach ($chauffeurs as $c)
                                <option value="{{ $c->id }}" @selected($commande->chauffeur_id == $c->id)>
                                    {{ $c->nom }}
                                </option>
                            @endforeach
                        </select>
                        <button class="px-4 py-2 bg-gray-800 text-white rounded text-sm">Affecter</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>