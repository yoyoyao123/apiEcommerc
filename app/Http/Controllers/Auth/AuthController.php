<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    

    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'phone' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
            'role' => 'string',
        ]);
        //si le role n'est pas spécifié, on le met par défaut à 'user' , tu peux switcher entre 'user' et 'admin'
        $request['password'] = \Hash::make($request['password']);
        $user = User::create($request->all());
        auth()->login($user);
        $accessToken = $user->createToken('authToken')->plainTextToken;
        return response()->json(['user' => $user, 'Authorization' => ['token_type' => 'Bearer', 'access_token' => $accessToken], 'message' => 'Utilisateur créé avec succès'], 201);
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        $credentiels = $request->only(['email', 'password']);
        if (!auth()->attempt($credentiels)){
            return response()->json(['message' => 'Vos identifiants sont incorrects'], 401);
        }
        $user = auth()->user();
        //expiration du token après 1 jour, tu peux changer la valeur de 'addDays(1)' à 'addHours(1)' pour une expiration après 1 heure ou 'addMinutes(30)' pour une expiration après 30 minutes
        $accessToken = $user->createToken('authToken', [$user->role], Carbon::now()->addDays(1))->plainTextToken;
        return response()->json(['user' => $user, 'Authorization' => ['token_type' => 'Bearer', 'access_token' => $accessToken], 'message' => 'Connexion réussie'], 200);
    }


    public function logout(Request $request)
    {
        //pour supprimer le token actuel de l'utilisateur
        $request->user()->currentAccessToken()->delete();
        //pour supprimer tous les tokens de l'utilisateur
        $request->user()->tokens()->each(function ($token, $key) {
            $token->delete();
        });
        //pour déconnecter l'utilisateur
        auth()->logout();
        //pour invalider la session de l'utilisateur
        session()->invalidate();
        session()->regenerateToken();
        return response()->json(['message' => 'Déconnexion réussie'], 200);
    }

}
