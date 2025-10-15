<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// CONTROLLER POUR LA GESTION DE L'AUTHENTIFICATION DES UTILISATEURS

class AuthController extends Controller
{
    // FONCTION INSCRIPTION
    public function register(Request $request)
    {
        $fields = $request->validate([
            'pseudo' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed|min:6'
        ]);

        $user = User::create([
            'pseudo' => $fields['pseudo'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    // FONCTION CONNEXION
    public function login(Request $request)
    {
        $fields = $request->validate([
            'pseudo' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('pseudo', $fields['pseudo'])->first();

        // VÉRIFIE SI L'UTILISATEUR EXISTE ET SI LE MOT DE PASSE EST VALIDE
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }

        // GÉNERE UN NOUVEAU TOKEN D'AUTHENTIFICATION POUR L'UTILISATEUR
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        // SUPPRIME TOUS LES JETONS DE L'UTILISATEUR CONNECTÉ
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Déconnecté avec succès'
        ]);
    }

    public function user(Request $request)
    {
        // RETOURNE LES INFOS DE L'UTILISATEUR AUTHENTIFIÉ OU ERREUR 401 SI NON CONNECTÉ
        $user = $this->getAuthenticatedUser($request);
        return $user ?: response()->json(['message' => 'Unauthenticated'], 401);
    }

    protected function getAuthenticatedUser(Request $request)
    {
        // RÉCUPÈRE L'UTILISATEUR À PARTIR DU TOKEN D'AUTHENTIFICATION
        $token = $this->getTokenFromRequest($request);
        if (!$token) {
            return null;
        }

        $user = \Laravel\Sanctum\PersonalAccessToken::findToken($token)->tokenable;
        return $user ? ['id' => $user->id, 'pseudo' => $user->pseudo, 'is_admin' => (bool)$user->is_admin] : null;
    }

    private function getTokenFromRequest($request)
    {
        // EXTRAIT LE TOKEN DEPUIS LE HEADER AUTHORIZATION
        $header = $request->header('Authorization');
        if ($header && preg_match('/Bearer\s+(\S+)/', $header, $matches)) {
            return $matches[1];
        }
        return $request->session()->get('api_token');
    }
}
