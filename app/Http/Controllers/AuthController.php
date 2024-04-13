<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function userListe()
    {
        $user = users::get();
        return response()->json([
            "status"=>1,
            "message"=> "liste des utilisateur recuperer  ",
            "data"=>$user

        ],200);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ajouterUser(Request $request)
{
    $validator = Validator::make($request->all(), [
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'contacte' => 'required|string|max:255|unique:users',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        
    ]);

 // Si la validation échoue, retourner les erreurs
 if ($validator->fails()) {
    return response()->json([
        'status' => 0,
        'errors' => $validator->errors(),
    ], 400);
}

    $user = new Users();

$user = Users::create([
        'nom' => $request->nom,
        'prenom' => $request->prenom,
        'contacte' => $request->contacte,
        'email' => $request->email,
        'password' => ($request->password),
        
    ]);

    $user->remember_token = Str::random(100);
    $user->save();
    return response()->json([
        "status"=>1,
        "message"=> "utilisateur creer avec success",
        "data"=> $user,
    ],200);
    
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getUser($id)
    {
        $user = Users::where("id",$id)->exists();
        if($user){
            $info = Users::find($id);
            return response()->json([
                "status"=>1,
                "message"=> "utilisateur trouvé",
                "data"=>$info
            ],200);
        } else {
            return response()->json([
                "status"=>0,
                "message"=> "utilisateur inexistant",
                "data"=>$user
            ],404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateUser(Request $request, $id)
    {
        $user = Users::findOrFail($id);
        $user->update($request->all());
        
        return response()->json(['message' => 'utilisateur mis à jour avec succès', 'data' => $user], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function suprimerUser($id)
    {
        Users::findOrFail($id)->delete();
        return response()->json(['message' => 'utilisateur supprimé avec succès'], 200);
    }


    
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Connexion refusée, veuillez vérifier vos informations'], 401);
        }
        //revoquer les anciens tokens
        //auth()->user()->tokens()->delete();

        $token = auth()->user()->createToken('ApiToken')->plainTextToken;
        $user = auth()->user();
        return response()->json([
            'user' => $user,
            'message' => 'Connexion effectuée avec succès',
            'Authorization' => [
                'token' => $token,
                'type' => 'Bearer'
            ]
      ],200);
} 

}
