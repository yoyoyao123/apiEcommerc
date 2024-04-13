<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function getUserCart(Request $request)
    {
        $cart = Cart::where('user_id', $request->user()->id)->first();
        $cart->load('products');
        return response()->json(['cart' => $cart], 200);
    }
    
    public function addProductToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
        ]);
        $product = Product::find($request['product_id']);
        if (!$product) {
            return response()->json(['message' => 'Produit introuvable'], 404);
        }
        //check if $use has cart else create
        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);
        $cartItem = CartProduct::where('cart_id', $cart->id)->where('product_id', $product->id)->first();
        if ($cartItem) {
            $cartItem->quantity += $request['quantity'];
            $cartItem->price = $product->price * $cartItem->quantity;
            $cartItem->save();
        }
        $cartItem = new CartProduct();
        $cartItem->cart_id = $cart->id;
        $cartItem->product_id = $product->id;
        $cartItem->quantity = $request['quantity'];
        $cartItem->price = $product->price * $cartItem->quantity;
        $cartItem->save();

        //retourner le panier de l'utilisateur
        $userCart = cart::where('user_id', $request->user()->id)->first();
        $userCart->load('products');
        return response()->json(['message' => 'Produit ajouté au panier avec succès', 'cart' => $userCart], 200);
    }

    public function removeProductFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
        ]);
        $product = Product::find($request['product_id']);
        if (!$product) {
            return response()->json(['message' => 'Produit introuvable'], 404);
        }
        $cart = $request->user()->cart;
        $cartItem = CartProduct::where('cart_id', $cart->id)->where('product_id', $product->id)->first();
        if (!$cartItem) {
            return response()->json(['message' => 'Produit introuvable dans le panier'], 404);
        }
        $cartItem->delete();

        $cart->load('products');
        return response()->json(['message' => 'Produit retiré du panier avec succès', 'cart' => $cart], 200);
    }

    public function getCart(Request $request)
    {
        $cart = $request->user()->cart;
        $cart->load('products');
        return response()->json(['cart' => $cart], 200);
    }



    public function clearCart(Request $request)
    {
        $cart = $request->user()->cart;
        foreach ($cart->products as $product) {
            $product->delete();
        }
        return response()->json(['message' => 'Panier vidé avec succès'], 200);
    }

    public function updateProductQuantity(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
        ]);
        $product = Product::find($request['product_id']);
        if (!$product) {
            return response()->json(['message' => 'Produit introuvable'], 404);
        }
        $cart = $request->user()->cart;
        $cartItem = CartProduct::where('cart_id', $cart->id)->where('product_id', $product->id)->first();
        if (!$cartItem) {
            return response()->json(['message' => 'Produit introuvable dans le panier'], 404);
        }
        $cartItem->quantity = $request['quantity'];
        $cartItem->price = $product->price * $cartItem->quantity;
        $cartItem->save();

        $cart->load('products');
        return response()->json(['message' => 'Quantité du produit mise à jour avec succès', 'cart' => $cart], 200);
    }



    public function getAllCarts(Request $request)
    {
        $carts = Cart::all();
        $carts->load('products');
        return response()->json(['carts' => $carts], 200);
    }
}
