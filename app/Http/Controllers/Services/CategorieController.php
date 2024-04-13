<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    
    public function getAllCategories()
    {
        $categories = Categorie::all();
        return response()->json(['categories' => $categories], 200);
    }


    public function getCategory($id)
    {
        $category = Categorie::find($id);
        if (!$category) {
            return response()->json(['message' => 'Catégorie introuvable'], 404);
        }
        return response()->json(['category' => $category], 200);
    }


    public function addCategory(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|unique:categories',
        ]);
        $category = Categorie::create($request->all());
        return response()->json(['category' => $category, 'message' => 'Catégorie créée avec succès'], 201);
    }


    


    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|unique:categories,nom,' . $id,
        ]);
        $category = Categorie::find($id);
        $category->with('products');

        if (!$category) {
            return response()->json(['message' => 'Catégorie introuvable'], 404);
        }
        $category->update($request->all());
        return response()->json(['category' => $category, 'message' => 'Catégorie modifiée avec succès'], 200);
    }



    public function deleteCategory($id)
    {
        $category = Categorie::find($id);
        if (!$category) {
            return response()->json(['message' => 'Catégorie introuvable'], 404);
        }
        $category->delete();
        return response()->json(['message' => 'Catégorie supprimée avec succès'], 200);
    }
}
