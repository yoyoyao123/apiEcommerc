<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
    /**
     * Récupérer tous les utilisateurs
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllUsers()
    {
        $users = User::all();
        return response()->json(['users' => $users], 200);
    }


    /**
     * Récupérer un utilisateur
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser(int $id)
    {
        $user = User::find($id);
        return response()->json(['user' => $user], 200);
    }


    /**
     * Supprimer un utilisateur
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);
        if ($request->user_id == $request->user()->id) {
            return response()->json(['message' => 'Vous ne pouvez pas supprimer votre propre compte  à partir de cet endpoint, veuillez utiliser le endpoint approprié ('.route('user.destroyAccount').')'], 400);
        }
        $user = User::find($request->user_id);
        $user->delete();
        return response()->json(['message' => 'Utilisateur supprimé avec succès'], 200);
    }
}
