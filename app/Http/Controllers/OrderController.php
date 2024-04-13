<?php

namespace App\Http\Controllers;

use App\Models\Commands;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function orderListe()
        {
            $orders = Commands::all();
            return response()->json([
                "status" => 1,
                "message" => "Liste des commandes",
                "data" => $orders
            ], 200);
        }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ajouterOrder(Request $request)
      {
        
    // Validation des données entrées
    $validator = Validator::make($request->all(), [
        'users_id' => 'required|exists:users,id',
        'carts_id' => 'required|exists:carts,id',
        'total' => 'required|numeric',
        'statut' => 'string',
    ]);

    // Vérifier si la validation a échoué
    if ($validator->fails()) {
        return response()->json([
            'status' => 0,
            'message' => 'Échec de la validation des données.',
            'errors' => $validator->errors(),
        ], 422); // Retourne un statut 422 (Unprocessable Entity) en cas d'échec de validation
    }

    // Obtenir les données validées
    $validatedData = $validator->validated();

    // Création de la commande
    $order = Commands::create([
        'users_id' => $validatedData['users_id'],
        'total' => $validatedData['total'],
        'statut' => $validatedData['statut'],
    ]);

    // Retourner le statut de la commande créée en plus de la réponse de création
    return response()->json([
        'status' => 1,
        'message' => 'Commande créée avec succès.',
        'data' => [
            'order' => $order,
            'statut' => $order->statut,
        ]
    ]);
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getOrder( $orderId)
    {
        $order = Commands::where("id",$orderId)->exists();
        if($order){
            $info = Commands::find($orderId);
            return response()->json([
                "status"=>1,
                "message"=> "commande  trouvé",
                "data"=>$info,
            ],200);
        }else{
            return response()->json([
                "status"=>0,
                "message"=> "commande inexistant ",
                "data"=>$order
    
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
    public function updateOrder(Request $request, $orderId)
    {
        // Récupérer la commande à mettre à jour
        $order = Commands::findOrFail($orderId);

        // Valider les données de la requête
        $validatedData = $request->validate([
            'total' => 'required|numeric',
             // Assurez-vous que le statut est valide
        ]);

        // Mettre à jour les champs de la commande avec les données validées
        $order->total = $validatedData['total'];

        // Enregistrer les modifications apportées à la commande
        $order->save();

        // Retourner une réponse JSON indiquant que la commande a été mise à jour avec succès
        return response()->json([
            'status' => 'success',
            'message' => 'La commande a été mise à jour avec succès.',
            'data' => $order, // Vous pouvez retourner la commande mise à jour si nécessaire
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function suprimerOrder($orderId)
    {
        Commands::findOrFail($orderId)->delete();
        return response()->json(['message' => 'commande supprimé avec succès'], 200);
    }
//status//

    public function updateOrderStatus($orderId, Request $request) {
        // Validation des données entrées
    $validator = Validator::make($request->all(), [
        'statut' => 'required|string|in:en attente,effectuée', 
        // Définir les valeurs acceptées pour le statut
    ]);

    // Vérifier si la validation a échoué
    if ($validator->fails()) {
        return response()->json([
            'status' => 0,
            'message' => 'Échec de la validation des données.',
            'errors' => $validator->errors(),
        ], 422); // Retourne un statut 422 (Unprocessable Entity) en cas d'échec de validation
    }

    // Récupérer la commande en fonction de son identifiant
    $order = Commands::find($orderId);

    // Vérifier si la commande existe
    if(!$order) {
        return response()->json([
            'status' => 0,
            'message' => 'La commande n\'existe pas.'
        ], 404); // Retourne un statut 404 si la commande n'est pas trouvée
    }

    // Mettre à jour le statut de la commande avec le statut fourni dans la requête
    $order->statut = $request->statut;
    $order->save();

    // Retourner le statut de la commande mis à jour
    return response()->json([
        'status' => 1,
        'message' => 'Statut de la commande mis à jour avec succès.',
        'data' => [
            'statut' => $order->statut,
        ]
    ]);
}
    
}
