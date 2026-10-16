<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommandeResource;
use App\Models\Commande;
use Illuminate\Http\Request;

class LivraisonController extends Controller
{
    // Un chauffeur ne peut toucher qu'à ses propres commandes
    private function checkOwner(Request $request, Commande $commande): void
    {
        abort_unless(
            $commande->chauffeur_id === $request->user()->chauffeur?->id,
            403,
            'Cette livraison ne vous est pas affectée.'
        );
    }

    public function index(Request $request){
        $chauffeur = $request->user()->chauffeur;
        abort_unless($chauffeur, 403, 'Profil chauffeur introuvable.');

        $commandes = Commande::with('client')
            ->where('chauffeur_id', $chauffeur->id)
            ->latest()->get();

        return CommandeResource::collection($commandes);
    }

    public function show(Request $request, Commande $commande)
    {
        $this->checkOwner($request, $commande);

        return new CommandeResource($commande->load('client'));
    }

    public function updateStatut(Request $request, Commande $commande)
    {
        $this->checkOwner($request, $commande);

        $data = $request->validate(['statut' => 'required|in:en_cours,livree']);

        $transitions = ['affectee' => 'en_cours', 'en_cours' => 'livree'];

        if (($transitions[$commande->statut] ?? null) !== $data['statut']) {
            return response()->json(['message' => 'Transition de statut invalide'], 422);
        }

        $commande->update(['statut' => $data['statut']]);

        return new CommandeResource($commande->load('client'));
    }

    public function addNote(Request $request, Commande $commande)
    {
        $this->checkOwner($request, $commande);

        $data = $request->validate(['note' => 'required|string|max:1000']);
        $commande->update(['note' => $data['note']]);

        return new CommandeResource($commande->load('client'));
    }
}
