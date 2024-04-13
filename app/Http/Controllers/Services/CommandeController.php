<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    
    public function getAllCommandes()
    {
        $commandes = Commande::all();
        return response()->json(['commandes' => $commandes], 200);
    }

    public function getPendingCommandes()
    {
        $commandes = Commande::where('status', 'en attente')->get();
        return response()->json(['commandes' => $commandes], 200);
    }


    public function getCommande($id)
    {
        $commande = Commande::find($id);
        if (!$commande) {
            return response()->json(['message' => 'Commande introuvable'], 404);
        }
        return response()->json(['commande' => $commande], 200);
    }


    public function getMyCommandes(Request $request)
    {
        $commandes = Commande::where('user_id', $request->user()->id)->get();
        return response()->json(['commandes' => $commandes], 200);
    }



    public function getMyCommandesByStatus(Request $request, $status)
    {
        $commandes = Commande::where('user_id', $request->user()->id)->where('status', $status)->get();
        return response()->json(['commandes' => $commandes], 200);
    }


    public function addCommande(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'cart_id' => 'required|integer|exists:carts,id',
            'total' => 'required|numeric',
        ]);
        $commande = Commande::create($request->all());
        return response()->json(['commande' => $commande, 'message' => 'Commande créée avec succès'], 201);
    }


    public function updateCommande(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);
        $commande = Commande::find($id);
        if (!$commande) {
            return response()->json(['message' => 'Commande introuvable'], 404);
        }
        $commande->update($request->all());
        return response()->json(['commande' => $commande, 'message' => 'Commande modifiée avec succès'], 200);
    }


    public function deleteCommande($id)
    {
        $commande = Commande::find($id);
        if (!$commande) {
            return response()->json(['message' => 'Commande introuvable'], 404);
        }
        $commande->delete();
        return response()->json(['message' => 'Commande supprimée avec succès'], 200);
    }


    public function getCommandesByUser($id)
    {
        $commandes = Commande::where('user_id', $id)->get();
        return response()->json(['commandes' => $commandes], 200);
    }


    public function getCommandesByStatus($status)
    {
        $commandes = Commande::where('status', $status)->get();
        return response()->json(['commandes' => $commandes], 200);
    }

}
