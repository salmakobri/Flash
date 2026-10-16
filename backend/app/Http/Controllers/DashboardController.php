<?php

namespace App\Http\Controllers;

use App\Models\Chauffeur;
use App\Models\Commande;

class DashboardController extends Controller
{
    public function index()
    {
        $parStatut = Commande::selectRaw('statut, count(*) as total')
            ->groupBy('statut')
            ->pluck('total', 'statut');

        $livreesAujourdhui = Commande::where('statut', Commande::LIVREE)
            ->whereDate('updated_at', today())
            ->count();

        $topChauffeurs = Chauffeur::withCount([
            'commandes as livrees_count' => fn ($q) => $q->where('statut', Commande::LIVREE),
        ])->orderByDesc('livrees_count')->take(5)->get();

        return view('dashboard', compact('parStatut', 'livreesAujourdhui', 'topChauffeurs'));
    }
}