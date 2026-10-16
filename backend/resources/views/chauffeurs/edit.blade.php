<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Modifier le chauffeur</h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8 bg-white shadow rounded p-6">
            <form method="POST" action="{{ route('chauffeurs.update', $chauffeur) }}">
                @csrf
                @method('PUT')
                @include('chauffeurs._form')
            </form>
        </div>
    </div>
</x-app-layout>
