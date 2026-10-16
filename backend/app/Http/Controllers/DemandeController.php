<?php

namespace App\Http\Controllers;

use App\Models\Chauffeur;
use App\Models\Demande;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DemandeController extends Controller
{
    public function index(Request $request)
    {
        $demandes = Demande::query()
            ->when($request->type, fn ($q, $v) => $q->where('type', $v))
            ->when($request->statut, fn ($q, $v) => $q->where('statut', $v))
            ->orderByRaw("statut = 'en_attente' desc") // les demandes en attente d'abord
            ->latest()
            ->paginate(15)->withQueryString();

        return view('demandes.index', compact('demandes'));
    }

    public function show(Demande $demande)
    {
        return view('demandes.show', compact('demande'));
    }

    public function accepter(Demande $demande)
    {
        if ($demande->statut !== Demande::EN_ATTENTE) {
            return back()->withErrors('Cette demande a déjà été traitée.');
        }

        if ($demande->type === Demande::RIDER && User::where('email', $demande->email)->exists()) {
            return back()->withErrors('Un compte existe déjà avec cet email : impossible de créer le livreur.');
        }

        DB::transaction(function () use ($demande) {
            $userId = null;

            if ($demande->type === Demande::RIDER) {
                $user = User::create([
                    'name' => $demande->nom,
                    'email' => $demande->email,
                    'password' => $demande->password, // déjà hashé à l'envoi de la demande
                    'role' => 'chauffeur',
                ]);
                Chauffeur::create(['user_id' => $user->id, 'nom' => $demande->nom]);
                $userId = $user->id;
            }

            $demande->update([
                'statut' => Demande::ACCEPTEE,
                'user_id' => $userId,
                'password' => null,
                'traitee_le' => now(),
            ]);
        });

        $message = $demande->type === Demande::RIDER
            ? "Demande acceptée : le compte livreur est créé, il peut se connecter à l'application."
            : 'Demande acceptée. Contactez le partenaire pour la suite.';

        return redirect()->route('demandes.show', $demande)->with('success', $message);
    }

    public function refuser(Request $request, Demande $demande)
    {
        if ($demande->statut !== Demande::EN_ATTENTE) {
            return back()->withErrors('Cette demande a déjà été traitée.');
        }

        $data = $request->validate(['note_admin' => 'nullable|string|max:500']);

        // Le mot de passe hashé est conservé pour que le livreur voie « demande refusée » à la connexion
        $demande->update([
            'statut' => Demande::REFUSEE,
            'note_admin' => $data['note_admin'] ?? null,
            'traitee_le' => now(),
        ]);

        return redirect()->route('demandes.show', $demande)->with('success', 'Demande refusée.');
    }
}