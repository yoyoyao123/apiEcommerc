<?php

namespace App\Http\Controllers;


use App\Models\products;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function producteListe ()
    {
        $product = products::get();
        return response()->json([
            "status"=>1,
            "message"=> "liste des produits recuperer  ",
            "data"=>$product

        ],200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ajouterProduct(Request $request)
    {
        $request->validate([

            "nom"=>"required",
            "description"=>"required",
            "prix"=>"required",
            "categorie-id"=>"required",
            "quantite"=>"required",
            "image",
            
        ]);

        $product = new products();

    // Remplir les propriétés du produit avec les données du formulaire
    $product->nom = $request->input('nom');
    $product->prix = $request->input('prix');
    $product->categorie_id = $request->input('categorie-id'); // Assurez-vous que cette valeur est correcte
    $product->description = $request->input('description');
    $product->quantite = $request->input('quantite');

    // Enregistrer le produit dans la base de données
    $product->save();


    return response()->json([
        "status"=>1,
        "message"=> "product creer avec success",

    ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getProduct($id)
    {
        $product = products::where("id",$id)->exists();
        if($product){

            $info = products::find($id);

            return response()->json([
                "status"=>1,
                "message"=> "produit  trouvé",
                "data"=>$info,
    
            ],200);
            
        }else{
            return response()->json([
                "status"=>0,
                "message"=> "produit inexistant ",
                "data"=>$product
    
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
    public function updateProduct(Request $request, $id)
    {
        $product = products::findOrFail($id);
        $product->update($request->all());
        
        return response()->json(['message' => 'Produit mis à jour avec succès', 'data' => $product], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function suprimerProduct($id)
    {
        products::findOrFail($id)->delete();
        return response()->json(['message' => 'Produit supprimé avec succès'], 200);
    }
}
