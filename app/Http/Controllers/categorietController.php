<?php

namespace App\Http\Controllers;

use App\Models\categorie;
use Illuminate\Http\Request;

class categorietController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function categorieListe()
    {
        $categorie = categorie::get();
        return response()->json([
            "status"=>1,
            "message"=> "liste des categorie recuperer  ",
            "data"=>$categorie

        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ajouterCategorie(Request $request)
        {
            $request->validate([
            "nom"=>"required",
            
        ]);

        $categorie = new categorie();
        $categorie ->nom = $request->nom;
   
        $categorie ->save();

        return response()->json([
        "status"=>1,
        "message"=> "categorie creer avec success",
    ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getCategorie($id)
    {
        $categorie = categorie::where("id",$id)->exists();
        if($categorie){
            $info = categorie::find($id);
            return response()->json([
                "status"=>1,
                "message"=> "categorie  trouvé",
                "data"=>$info,
            ],200);
        }else{
            return response()->json([
                "status"=>0,
                "message"=> "categorie inexistant ",
                "data"=>$categorie
    
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
    public function updateCategorie(Request $request, $id)
    {
        $categorie = categorie::findOrFail($id);
        $categorie->update($request->all());
        
        return response()->json(['message' => 'categorie mis à jour avec succès', 'data' => $categorie], 200);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function suprimerCategorie($id)
    {
        categorie::findOrFail($id)->delete();
        return response()->json(['message' => 'categorie supprimé avec succès'], 200);
    }
}
