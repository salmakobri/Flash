<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
    <input type="text" name="nom" value="{{ old('nom', isset($chauffeur) ? $chauffeur->nom : '') }}"
           class="w-full border-gray-300 rounded">
    @error('nom') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Email (identifiant de connexion)</label>
    <input type="email" name="email"
           value="{{ old('email', isset($chauffeur) ? $chauffeur->user->email : '') }}"
           class="w-full border-gray-300 rounded">
    @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Mot de passe @isset($chauffeur) (laisser vide pour ne pas le changer) @endisset
    </label>
    <input type="password" name="password" class="w-full border-gray-300 rounded">
    @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div class="flex gap-3">
    <button class="px-4 py-2 bg-gray-800 text-white rounded text-sm">Enregistrer</button>
    <a href="{{ route('chauffeurs.index') }}" class="px-4 py-2 border rounded text-sm">Annuler</a>
</div>
