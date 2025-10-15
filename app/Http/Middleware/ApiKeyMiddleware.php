<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiKey;
use Illuminate\Support\Facades\Log;


class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // RÉCUPÈRE LA CLEF API DEPUIS LE BEARER TOKEN, LE HEADER OU LES PARAMETRES DE REQUETE
        $apiKey = $request->bearerToken()
            ?? $request->header('X-API-KEY')
            ?? $request->query('api_key');

        Log::info('API KEY recue', ['apiKey' => $apiKey]);

        // VERIFIE QUE LA CLÉ EXISTE ET EST ACTIVE DANS LA BASE
        $key = ApiKey::where('cle_api', $apiKey)->where('status', 'Actif')->first();
        Log::info('Key trouvé', ['key' => $key]);

        // REFUSE L'ACCÈS SI LA CLÉ EST INVALIDE OU ABSENTE
        if (!$key) {
            return response()->json(['message' => 'Clé API invalide ou manquante'], 401);
        }

        return $next($request);
    }
}
