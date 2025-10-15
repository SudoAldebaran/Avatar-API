<?php

namespace App\Http\Controllers;

use App\Models\AvatarComplet;
use Illuminate\Http\JsonResponse;

// CONTROLLER POUR RECUPERER LES AVATARS (NOM ET SVG) DEPUIS L'API

class AvatarApiController extends Controller
{
    public function allAvatarsMinimal(): JsonResponse
    {
        // SÉLECTIONNE UNIQUEMENT LES CHAMPS avatar_name ET avatar_svg POUR TOUS LES AVATARS
        $avatars = AvatarComplet::select('avatar_name', 'avatar_svg')->get();

        return response()->json($avatars);
    }
}
