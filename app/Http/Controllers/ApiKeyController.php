<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

// CONTROLLER POUR GERER LES CLEFS API

class ApiKeyController extends Controller
{
    public function index(Request $request)
    {
        // VERIFIE SI L'UTILISATEUR EST ADMIN AVANT D'AFFICHER LES CLÉS API
        $user = $this->getCurrentUser($request);
        if (!$user || !$user['is_admin']) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Accès non autorisé'], 403)
                : redirect('/')->with('error', 'Accès non autorisé');
        }
        $apiKeys = ApiKey::all();
        return $request->expectsJson()
            ? response()->json($apiKeys)
            : view('api-keys', compact('apiKeys'));
    }

    public function generate(Request $request)
    {
        // SEULEMENT UN ADMIN PEUT GÉNÉRER UNE NOUVELLE CLEF API
        $user = $this->getCurrentUser($request);
        if (!$user || !$user['is_admin']) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Accès non autorisé'], 403)
                : redirect('/')->with('error', 'Accès non autorisé');
        }

        $request->validate(['raison_sociale' => 'required|string|max:50']);
        try {
            // CRÉATION DE LA CLÉ API AVEC UNE CHAINE ALEATOIRE DE 100 CARACTÈRES
            $apiKey = ApiKey::create([
                'cle_api' => Str::random(100),
                'raison_sociale' => $request->raison_sociale,
                'status' => 'Actif',
            ]);
            return $request->expectsJson()
                ? response()->json(['message' => 'Clé API générée avec succès', 'key' => $apiKey->cle_api])
                : redirect()->back()->with('success', 'Clé API générée avec succès');
        } catch (\Exception $e) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Erreur lors de la génération'], 500)
                : redirect()->back()->with('error', 'Erreur lors de la génération');
        }
    }

    public function toggleStatus(Request $request, $id)
    {
        // PERMET DE CHANGER LE STATUT ACTIF/INACTIF D'UNE CLÉ API
        $user = $this->getCurrentUser($request);
        if (!$user || !$user['is_admin']) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $apiKey = ApiKey::find($id);
        if (!$apiKey) {
            return response()->json(['message' => 'Clé non trouvée'], 404);
        }

        $apiKey->status = $apiKey->status === 'Actif' ? 'Inactif' : 'Actif';
        $apiKey->save();

        return response()->json(['message' => 'Statut mis à jour', 'status' => $apiKey->status]);
    }

    public function delete(Request $request, $id)
    {
        // SUPPRESSION D'UNE CLÉ API PAR UN ADMIN
        $user = $this->getCurrentUser($request);
        if (!$user || !$user['is_admin']) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $apiKey = ApiKey::find($id);
        if (!$apiKey) {
            return response()->json(['message' => 'Clé non trouvée'], 404);
        }

        $apiKey->delete();
        return response()->json(['message' => 'Clé supprimée avec succès']);
    }

    private function getCurrentUser($request)
    {
        // RÉCUPERE L'UTILISATEUR À PARTIR DU TOKEN D'AUTHENTIFICATION
        $token = $this->getTokenFromRequest($request);
        if (!$token) {
            return null;
        }

        try {
            $accessToken = PersonalAccessToken::findToken($token);
            if (!$accessToken) {
                return null;
            }
            $user = $accessToken->tokenable;
            return $user ? ['id' => $user->id, 'is_admin' => (bool)$user->is_admin] : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getTokenFromRequest($request)
    {
        // EXTRAIT LE TOKEN DEPUIS LE HEADER AUTHORIZATION
        $header = $request->header('Authorization');
        if ($header && preg_match('/Bearer\s+(\S+)/', $header, $matches)) {
            return $matches[1];
        }
        return $request->session()->get('api_token') ?: (request()->expectsJson() ? null : session('api_token'));
    }

    // LISTE TOUTES LES CLÉS API POUR UN ADMIN
    public function list(Request $request)
    {
        $user = $this->getCurrentUser($request);
        if (!$user || !$user['is_admin']) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }
        return response()->json(ApiKey::all());
    }
}
