<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\categorietController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;






/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/users', function (Request $request) {
    return $request->users();
});


//productRoute//
Route::get("product-list", [ProductController::class, 'producteListe']);
Route::get("product/{id}" , [ProductController::class, 'getProduct']);
Route::post("ajouter-product" , [ProductController::class, 'ajouterProduct']);
Route::put("modifier-product/{id}" , [ProductController::class, 'updateProduct']);
Route::delete("suprimer-product/{id}" , [ProductController::class, 'suprimerProduct']);

//categori//

Route::get("categorie-list", [categorietController::class, 'categorieListe']);
Route::get("categorie/{id}" , [categorietController::class, 'getCategorie']);
Route::post("ajouter-categorie" , [categorietController::class, 'ajouterCategorie']);
Route::put("modifier-categorie/{id}" , [categorietController::class, 'updateCategorie']);
Route::delete("suprimer-categorie/{id}" , [categorietController::class, 'suprimerCategorie']);


//user rout//
Route::get("users-list", [AuthController::class, 'userListe']);
Route::get("users/{id}" , [AuthController::class, 'getUser']);
Route::post("register" , [AuthController::class, 'ajouterUser']);
Route::put("modifier-user/{id}" , [AuthController::class, 'updateUser']);
Route::post("login", [AuthController::class, 'login']);
Route::delete("suprimer-user/{id}" , [AuthController::class, 'suprimerUser']);


// ORDER//
Route::get("order-list", [OrderController::class, 'orderListe']);
Route::get("order/{orderId}" , [OrderController::class, 'getOrder']);
Route::post("create_order" , [OrderController::class, 'ajouterOrder']);
Route::put("modifier-order/{orderId}" , [OrderController::class, 'updateOrder']);
Route::put("orders/{orderId}/status", [OrderController::class, 'updateOrderStatus']);
Route::delete("suprimer-order/{orderId}" , [OrderController::class, 'suprimerOrder']);
 
//CARTS//
Route::get("cart-list", [ CartController::class, 'cartListe']);
Route::get("cart/{cartId}" , [ CartController::class, 'gecart']);
Route::post("create_cart" , [ CartController::class, 'ajouterCart']);
Route::put("modifier-cart/{cartId}" , [ CartController::class, 'updateCart']);
Route::delete("suprimer-cart/{cartId}" , [ CartController::class, 'suprimerCart']);
 