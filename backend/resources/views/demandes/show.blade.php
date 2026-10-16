<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Demande #{{ $demande->id }} : {{ $demande->type === 'rider' ? 'Livreur' : 'Magasin partenaire' }}
            </h2>
            <a href="{{ route('demandes.index') }}" class="text-sm text-blue-600">&larr; Retour à la liste</a>
        </div>
    </x-slot>

    @php
        $labels = ['en_attente' => 'En attente', 'acceptee' => 'Acceptée', 'refusee' => 'Refusée'];
        $badges = [
            'en_attente' => 'bg-orange-100 text-orange-800',
            'acceptee' => 'bg-green-100 text-green-800',
            'refusee' => 'bg-red-100 text-red-800',
        ];
    @endphp

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
                    <dd class="col-span-2">
                        <span class="px-2 py-1 rounded text-xs {{ $badges[$demande->statut] ?? '' }}">
                            {{ $labels[$demande->statut] ?? $demande->statut }}
                        </span>
                    </dd>

                    <dt class="text-gray-500">Reçue le</dt>
                    <dd class="col-span-2">{{ $demande->created_at->format('d/m/Y H:i') }}</dd>

                    <dt class="text-gray-500">Nom</dt>
                    <dd class="col-span-2">{{ $demande->nom }}</dd>

                    <dt class="text-gray-500">Email</dt>
                    <dd class="col-span-2">{{ $demande->email }}</dd>

                    <dt class="text-gray-500">Téléphone</dt>
                    <dd class="col-span-2">{{ $demande->telephone }}</dd>

                    <dt class="text-gray-500">Ville</dt>
                    <dd class="col-span-2">{{ $demande->ville }}</dd>

                    @if ($demande->type === 'rider')
                        <dt class="text-gray-500">Véhicule</dt>
                        <dd class="col-span-2">{{ ucfirst($demande->vehicule) }}</dd>
                    @else
                        <dt class="text-gray-500">Magasin</dt>
                        <dd class="col-span-2">{{ $demande->magasin }}</dd>

                        <dt class="text-gray-500">Type de magasin</dt>
                        <dd class="col-span-2">{{ ucfirst($demande->categorie) }}</dd>
                    @endif

                    <dt class="text-gray-500">Message</dt>
                    <dd class="col-span-2">{!! nl2br(e($demande->message ?? '—')) !!}</dd>

                    @if ($demande->traitee_le)
                        <dt class="text-gray-500">Traitée le</dt>
                        <dd class="col-span-2">{{ $demande->traitee_le->format('d/m/Y H:i') }}</dd>
                    @endif

                    @if ($demande->note_admin)
                        <dt class="text-gray-500">Motif du refus</dt>
                        <dd class="col-span-2">{{ $demande->note_admin }}</dd>
                    @endif
                </dl>
            </div>

            @if ($demande->statut === 'en_attente')
                <div class="bg-white shadow rounded p-6 flex flex-wrap gap-6 items-start">
                    <form method="POST" action="{{ route('demandes.accepter', $demande) }}"
                          onsubmit="return confirm('Accepter cette demande ?')">
                        @csrf
                        <button class="px-4 py-2 bg-green-600 text-white rounded text-sm">Accepter</button>
                        @if ($demande->type === 'rider')
                            <p class="mt-2 text-xs text-gray-500">Crée le compte livreur avec l'email et le mot de passe choisis.</p>
                        @endif
                    </form>

                    <form method="POST" action="{{ route('demandes.refuser', $demande) }}"
                          onsubmit="return confirm('Refuser cette demande ?')" class="flex-1 min-w-[16rem]">
                        @csrf
                        <input type="text" name="note_admin" placeholder="Motif du refus (optionnel)"
                               class="w-full border-gray-300 rounded text-sm mb-2">
                        <button class="px-4 py-2 bg-red-600 text-white rounded text-sm">Refuser</button>
                    </form>
                </div>
            @elseif ($demande->statut === 'acceptee' && $demande->user_id)
                <p class="text-sm text-gray-600">
                    Compte livreur créé. Il apparaît dans la liste des
                    <a href="{{ route('chauffeurs.index') }}" class="text-blue-600">chauffeurs</a>.
                </p>
            @endif
        </div>
    </div>
</x-app-layout>