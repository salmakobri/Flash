<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Client</label>
    <select name="client_id" class="w-full border-gray-300 rounded">
        <option value="">-- Choisir --</option>
        @foreach ($clients as $client)
            <option value="{{ $client->id }}"
                @selected(old('client_id', isset($commande) ? $commande->client_id : '') == $client->id)>
                {{ $client->nom }}
            </option>
        @endforeach
    </select>
    @error('client_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
    <input type="date" name="date"
           value="{{ old('date', isset($commande) ? $commande->date->format('Y-m-d') : now()->format('Y-m-d')) }}"
           class="w-full border-gray-300 rounded">
    @error('date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Adresse de livraison</label>
    <input type="text" name="adresse_livraison"
           value="{{ old('adresse_livraison', isset($commande) ? $commande->adresse_livraison : '') }}"
           class="w-full border-gray-300 rounded">
    @error('adresse_livraison') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Détails (optionnel)</label>
    <textarea name="details" rows="3" class="w-full border-gray-300 rounded">{{ old('details', isset($commande) ? $commande->details : '') }}</textarea>
    @error('details') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div class="flex gap-3">
    <button class="px-4 py-2 bg-gray-800 text-white rounded text-sm">Enregistrer</button>
    <a href="{{ route('commandes.index') }}" class="px-4 py-2 border rounded text-sm">Annuler</a>
</div>