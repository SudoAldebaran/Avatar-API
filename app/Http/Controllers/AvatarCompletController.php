<?php

namespace App\Http\Controllers;

use App\Models\AvatarComplet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// CONTROLLER POUR LA GESTION DES AVATARS COMPLETS ASSOCIÉS À L'UTILISATEUR

class AvatarCompletController extends Controller
{
    public function store(Request $request)
    {
        // VALIDATION DES CHAMPS REQUIS POUR L'AVATAR
        $request->validate([
            'avatar_svg' => 'required|string',
            'avatar_name' => 'required|string|max:255',
        ]);

        // CREATION D'UN NOUVEL AVATAR ASSOCIÉ À L'UTILISATEUR CONNECTÉ
        $avatar = AvatarComplet::create([
            'user_id' => Auth::id(),
            'avatar_svg' => $request->avatar_svg,
            'avatar_name' => $request->avatar_name,
        ]);

        return response()->json([
            'message' => 'Avatar sauvegardé avec succès.',
            'avatar_id' => $avatar->avatar_id,
            'avatar_name' => $avatar->avatar_name,
        ], 201);
    }

    public function index()
    {
        // RETOURNE TOUS LES AVATARS DE L'UTILISATEUR CONNECTÉ
        $avatars = AvatarComplet::where('user_id', Auth::id())->get();
        return response()->json($avatars);
    }
}
