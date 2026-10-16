@props(['statut'])
@php
    $styles = [
        'en_attente' => 'bg-gray-200 text-gray-800',
        'affectee'   => 'bg-blue-100 text-blue-800',
        'en_cours'   => 'bg-orange-100 text-orange-800',
        'livree'     => 'bg-green-100 text-green-800',
    ];
@endphp
<span class="px-2 py-1 rounded text-xs {{ $styles[$statut] ?? 'bg-gray-100' }}">
    {{ str_replace('_', ' ', $statut) }}
</span>