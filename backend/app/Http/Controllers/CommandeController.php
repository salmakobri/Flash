<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommandeRequest;
use App\Models\Chauffeur;
use App\Models\Client;
use App\Models\Commande;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    public function index(Request $request)
    {
        $commandes = Commande::with(['client', 'chauffeur'])
            ->when($request->statut, fn ($q, $v) => $q->where('statut', $v))
            ->when($request->chauffeur_id, fn ($q, $v) => $q->where('chauffeur_id', $v))
            ->when($request->date, fn ($q, $v) => $q->whereDate('date', $v))
            ->latest()->paginate(15)->withQueryString();

        return view('commandes.index', [
            'commandes' => $commandes,
            'chauffeurs' => Chauffeur::orderBy('nom')->get(),
        ]);
    }

    public function create()
    {
        return view('commandes.create', ['clients' => Client::orderBy('nom')->get()]);
    }

    public function store(CommandeRequest $request)
    {
        // Le statut est fixé côté serveur
        $commande = Commande::create($request->validated() + ['statut' => Commande::EN_ATTENTE]);

        return redirect()->route('commandes.show', $commande)->with('success', 'Commande créée.');
    }

    public function show(Commande $commande)
    {
        $commande->load(['client', 'chauffeur']);

        return view('commandes.show', [
            'commande' => $commande,
            'chauffeurs' => Chauffeur::orderBy('nom')->get(),
        ]);
    }

    public function edit(Commande $commande)
    {
        return view('commandes.edit', [
            'commande' => $commande,
            'clients' => Client::orderBy('nom')->get(),
        ]);
    }

    public function update(CommandeRequest $request, Commande $commande)
    {
        $commande->update($request->validated());

        return redirect()->route('commandes.show', $commande)->with('success', 'Commande modifiée.');
    }

    public function destroy(Commande $commande)
    {
        $commande->delete();

        return redirect()->route('commandes.index')->with('success', 'Commande supprimée.');
    }

    public function affecter(Request $request, Commande $commande)
    {
        $data = $request->validate(['chauffeur_id' => 'required|exists:chauffeurs,id']);

        if (in_array($commande->statut, [Commande::EN_COURS, Commande::LIVREE])) {
            return back()->withErrors('Impossible : cette commande est déjà en cours ou livrée.');
        }

        $commande->update([
            'chauffeur_id' => $data['chauffeur_id'],
            'statut' => Commande::AFFECTEE,
        ]);

        return back()->with('success', 'Chauffeur affecté.');
    }
}