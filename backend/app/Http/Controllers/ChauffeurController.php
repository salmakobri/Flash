<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChauffeurRequest;
use App\Models\Chauffeur;
use App\Models\Commande;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ChauffeurController extends Controller
{
    public function index()
    {
        $chauffeurs = Chauffeur::with('user')->withCount('commandes')
            ->orderBy('nom')->paginate(10);
        return view('chauffeurs.index', compact('chauffeurs'));
    }

    public function create()
    {
        return view('chauffeurs.create');
    }

    public function store(ChauffeurRequest $request)
    {
        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->nom,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'chauffeur',
            ]);
            Chauffeur::create(['user_id' => $user->id, 'nom' => $request->nom]);
        });

        return redirect()->route('chauffeurs.index')->with('success', 'Chauffeur créé.');
    }

    public function edit(Chauffeur $chauffeur)
    {
        $chauffeur->load('user');
        return view('chauffeurs.edit', compact('chauffeur'));
    }

    public function update(ChauffeurRequest $request, Chauffeur $chauffeur)
    {
        DB::transaction(function () use ($request, $chauffeur) {
            $data = ['name' => $request->nom, 'email' => $request->email];
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }
            $chauffeur->user->update($data);
            $chauffeur->update(['nom' => $request->nom]);
        });

        return redirect()->route('chauffeurs.index')->with('success', 'Chauffeur modifié.');
    }

    public function destroy(Chauffeur $chauffeur)
    {
        DB::transaction(function () use ($chauffeur) {
            // Ses commandes non terminées redeviennent "en attente"
            $chauffeur->commandes()
                ->whereIn('statut', [Commande::AFFECTEE, Commande::EN_COURS])
                ->update(['statut' => Commande::EN_ATTENTE]);

            // Supprimer le compte supprime aussi le chauffeur (cascade)
            $chauffeur->user->delete();
        });

        return redirect()->route('chauffeurs.index')->with('success', 'Chauffeur supprimé.');
    }
}