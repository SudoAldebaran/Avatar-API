<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AvatarComplet;
use Illuminate\Support\Facades\Auth;

// CONTROLLER POUR LA GESTION DE LA BIBLIOTHEQUE D'AVATARS

class BibliothequeController extends Controller
{
    public function index()
    {
        return view('bibliotheque');
    }

    public function recuperer()
    {
        // RETOURNE TOUS LES AVATARS DE L'UTILISATEUR CONNECTÉ
        $avatars = AvatarComplet::where('user_id', Auth::id())->get();
        return response()->json($avatars);
    }

    public function delete($id)
    {
        $user = Auth::user();

        // CHERCHE L'AVATAR PAR ID ET VÉRIFIE QU'IL APPARTIENT À L'UTILISATEUR CONNECTÉ
        $avatar = AvatarComplet::where('avatar_id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$avatar) {
            return response()->json(['message' => 'Avatar non trouvé ou accès refusé'], 404);
        }

        try {
            // SUPPRESSION DE L'AVATAR
            $avatar->delete();
            return response()->json(['message' => 'Avatar supprimé avec succès']);
        } catch (\Exception $e) {
            // ERREUR LORS DE LA SUPPRESSION
            return response()->json(['message' => 'Erreur lors de la suppression'], 500);
        }
    }
}
