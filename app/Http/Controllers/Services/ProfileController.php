<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    
    /**
     * Récupérer les informations de l'utilisateur connecté
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        return response()->json(['user' => $request->user()], 200);
    }


    /**
     * Mettre à jour les informations de l'utilisateur connecté
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateInfo(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'phone' => 'required|string|unique:users,phone,'.$request->user()->id,
            'email' => 'required|email|unique:users,email,'.$request->user()->id,
        ]);
        $user = $request->user();
        $user->update($request->all());
        return response()->json(['user' => $user], 200);
    }


    /**
     * Mettre à jour le mot de passe de l'utilisateur connecté
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string',
            'password_confirmation' => 'required|string|same:password',
        ]);
        $user = $request->user();
        if (!\Hash::check($request->old_password, $user->password)) {
            return response()->json(['message' => 'Ancien mot de passe incorrect'], 400);
        }
        $user->update(['password' => \Hash::make($request->password)]);
        return response()->json(['message' => 'Mot de passe mis à jour avec succès'], 200);
    }


    /**
     * Supprimer le compte de l'utilisateur connecté
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAccount(Request $request)
    {
        $user = $request->user();
        auth()->logout();
        $user->delete();
        return response()->json(['message' => 'Compte supprimé avec succès'], 200);
    }
}
