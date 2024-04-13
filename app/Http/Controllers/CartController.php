<?php

namespace App\Http\Controllers;

use App\Models\carts;
use App\Models\product;
use App\Models\products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getTotal($cart)
{
    $total = 0;

    foreach ($cart->products as $product) {
        $total += $product->pivot->price * $product->pivot->quantity;
    }

    return $total;
}
public function ajouterCart(Request $request)
{
    // Validation des données entrées
    $validator = Validator::make($request->all(), [
        'products_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
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

    // Vérifier si le produit existe déjà dans le panier
    $cart = carts::where('users_id', auth()->user()->id)
                ->first();

    // Si le produit n'existe pas dans le panier, créer un nouveau panier
    if (!$cart) {
        $cart = carts::create([
            'users_id' => auth()->user()->id,
        ]);
    }

    // Ajouter le produit au panier avec la quantité spécifiée
    $product = products::findOrFail($validatedData['products_id']);
    $cart->products()->attach($product->id, [
        'quantity' => $validatedData['quantity'],
        'price' => $product->prix,
    ]);

    // Calculer le montant total du panier
    $total = $this->getTotal($cart);

    // Retourner le statut de l'article créé en plus de la réponse de création
    return response()->json([
        'status' => 1,
        'message' => 'Article ajouté au panier avec succès.',
        'data' => $cart,
        'products' => $product,
        'total' => $total,
    ]);
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
