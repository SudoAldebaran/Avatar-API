<?php

namespace App\Http\Middleware;

use Closure;
use Laravel\Sanctum\PersonalAccessToken;

// MIDDLEWARE POUR VERIFIER SI L'UTILISATEUR EST UN ADMINISTRATEUR

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        // VERIFIE QUE L'UTILISATEUR EST ADMIN AVANT DE CONTINUER
        $user = $this->getCurrentUser($request);
        if (!$user || !$user['is_admin']) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Accès non autorisé'], 403)
                : redirect('/')->with('error', 'Accès non autorisé');
        }
        return $next($request);
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
        return $request->session()->get('api_token');
    }
}
