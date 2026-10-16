<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DemandeApiController extends Controller
{
    public function store(Request $request)
    {
        $type = $request->validate(['type' => 'required|in:rider,partner'])['type'];

        // Pas de doublon en attente ; un livreur ne peut pas avoir un email déjà utilisé
        $email = [
            'required', 'email', 'max:255',
            Rule::unique('demandes', 'email')->where(fn ($q) => $q->where('statut', Demande::EN_ATTENTE)),
        ];

        $rules = [
            'nom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'ville' => 'required|string|max:100',
            'message' => 'nullable|string|max:1000',
        ];

        if ($type === Demande::RIDER) {
            $email[] = Rule::unique('users', 'email');
            $rules += [
                'vehicule' => 'required|in:moto,scooter,velo,voiture',
                'password' => 'required|string|min:8|confirmed',
            ];
        } else {
            $rules += [
                'magasin' => 'required|string|max:255',
                'categorie' => 'required|in:restaurant,supermarche,pharmacie,autre',
            ];
        }
        $rules['email'] = $email;

        $data = $request->validate($rules, [
            'email.unique' => 'Cette adresse email est déjà utilisée ou une demande est déjà en cours.',
        ]);

        Demande::create([
            'type' => $type,
            'nom' => $data['nom'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'ville' => $data['ville'],
            'vehicule' => $data['vehicule'] ?? null,
            'magasin' => $data['magasin'] ?? null,
            'categorie' => $data['categorie'] ?? null,
            'message' => $data['message'] ?? null,
            'password' => $type === Demande::RIDER ? Hash::make($data['password']) : null,
            'statut' => Demande::EN_ATTENTE,
        ]);

        return response()->json(['message' => 'Votre demande a été envoyée.'], 201);
    }
}