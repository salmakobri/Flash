<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
    <input type="text" name="nom" value="{{ old('nom', $client->nom ?? '') }}"
           class="w-full border-gray-300 rounded">
    @error('nom') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
    <input type="text" name="telephone" value="{{ old('telephone', $client->telephone ?? '') }}"
           class="w-full border-gray-300 rounded">
    @error('telephone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div class="flex gap-3">
    <button class="px-4 py-2 bg-gray-800 text-white rounded text-sm">Enregistrer</button>
    <a href="{{ route('clients.index') }}" class="px-4 py-2 border rounded text-sm">Annuler</a>
</div>