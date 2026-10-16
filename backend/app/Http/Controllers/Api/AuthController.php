<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Demande;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Un livreur dont la demande n'est pas encore acceptée ?
            $demande = Demande::where('email', $request->email)->latest()->first();

            if ($demande && $demande->password && Hash::check($request->password, $demande->password)) {
                return response()->json([
                    'message' => match ($demande->statut) {
                        Demande::EN_ATTENTE => "Votre demande est en cours de validation par l'administrateur.",
                        Demande::REFUSEE => 'Votre demande a été refusée.',
                        default => 'Identifiants invalides',
                    },
                ], 403);
            }

            return response()->json(['message' => 'Identifiants invalides'], 401);
        }

        if (!Hash::check($request->password, $user->password)
            || !in_array($user->role, ['chauffeur', 'client'])) {
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }

        return response()->json([
            'token' => $user->createToken('app')->plainTextToken,
            'user' => ['id' => $user->id, 'name' => $user->name, 'role' => $user->role],
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'telephone' => 'nullable|string|max:20',
        ]);

        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'client',
            ]);
            Client::create([
                'user_id' => $user->id,
                'nom' => $data['name'],
                'telephone' => $data['telephone'] ?? '',
            ]);
            return $user;
        });

        return response()->json([
            'token' => $user->createToken('app')->plainTextToken,
            'user' => ['id' => $user->id, 'name' => $user->name, 'role' => 'client'],
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnecté']);
    }
}