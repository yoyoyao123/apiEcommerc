<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    
    public function getAllProducts()
    {
        $products = Product::all();
        return response()->json(['products' => $products], 200);
    }

    public function getProduct($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Produit introuvable'], 404);
        }
        return response()->json(['product' => $product], 200);
    }



    public function searchProduct(Request $request)
    {
        $request->validate([
            'search' => 'required|string',
        ]);
        $products = Product::where('name', 'like', '%' . $request['search'] . '%')->get();
        return response()->json(['products' => $products], 200);
    }


    public function filterProduct(Request $request)
    {
        $request->validate([
            'min_price' => 'required|numeric',
            'max_price' => 'required|numeric',
        ]);
        $products = Product::whereBetween('price', [$request['min_price'], $request['max_price']])->get();
        return response()->json(['products' => $products], 200);
    }


    public function getProductsByCategory($id)
    {
        $category = Categorie::find($id);
        if (!$category) {
            return response()->json(['message' => 'Catégorie introuvable'], 404);
        }
        $products = Product::where('categore_id', $id)->get();
        return response()->json(['products' => $products], 200);
    }



    public function createProduct(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id', //tu peux changer 'id' par 'category_id' si tu as renommé la colonne 'id' dans la table 'categories
            'name' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'image' => 'required|image',
        ]);
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $imageName);
        $product = new Product();
        $product->name = $request['name'];
        $product->price = $request['price'];
        $product->description = $request['description'];
        $product->image = "images/$imageName"; //le chemin de l'image dans le serveur, tu peux le changer selon ton besoin, par exemple "images/$imageName" ou "images/$imageName.jpg
        $product->save();
        return response()->json(['product' => $product, 'message' => 'Produit créé avec succès'], 201);
    }


    public function updateProduct(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Produit introuvable'], 404);
        }
        $request->validate([
            'category_id' => 'exists:categories,id', //tu peux changer 'id' par 'category_id' si tu as renommé la colonne 'id' dans la table 'categories
            'name' => 'string',
            'price' => 'numeric',
            'description' => 'string',
            'image' => 'image',
        ]);
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $product->image = "images/$imageName";
        }
        $product->name = $request['name'] ?? $product->name;
        $product->price = $request['price'] ?? $product->price;
        $product->description = $request['description'] ?? $product->description;
        $product->save();
        return response()->json(['product' => $product, 'message' => 'Produit modifié avec succès'], 200);
    }


    public function deleteProduct($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Produit introuvable'], 404);
        }
        $product->delete();
        return response()->json(['message' => 'Produit supprimé avec succès'], 200);
    }

}
