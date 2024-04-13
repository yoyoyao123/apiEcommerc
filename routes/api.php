<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Services\CartController;
use App\Http\Controllers\Services\CategorieController;
use App\Http\Controllers\Services\CommandeController;
use App\Http\Controllers\Services\ProductController;
use App\Http\Controllers\Services\ProfileController;
use App\Http\Controllers\Services\UserController;
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

//================================================================================================
//Les routes accessibles pour tout utilisateur (user, admin ...) connecté ou non
//================================================================================================
//Products
Route::get('products', [ProductController::class, 'getAllProducts']);
Route::get('products/{id}', [ProductController::class, 'getProduct']);
Route::get('products/search', [ProductController::class, 'searchProduct']);
Route::get('products/filter', [ProductController::class, 'filterProduct']);
Route::get('products/by-category/{id}', [ProductController::class, 'getProductsByCategory']);

//categories
Route::get('categories', [CategorieController::class, 'getAllCategories']);
Route::get('categories/{id}', [CategorieController::class, 'getCategory']);




Route::middleware('guest')->group(function () {
    //Auth
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);
});

//================================================================================================
//Les routes accessibles pour tout utilisateur connecté (user, admin ...)
//================================================================================================
Route::middleware('auth:sanctum')->group(function () {
    //Profile
    Route::get('users/me', [ProfileController::class, 'me']);
    Route::put('users/update_info', [ProfileController::class, 'updateInfo']);
    Route::put('users/update_password', [ProfileController::class, 'updatePassword']);
    Route::delete('users/delete', [ProfileController::class, 'deleteAccount'])->name('user.destroyAccount');


    //Cart
    Route::get('users/cart/my-cart', [CartController::class, 'getUserCart']);
    Route::post('users/cart/add', [CartController::class, 'addProductToCart']);
    Route::put('users/cart/update-quantity', [CartController::class, 'updateProductQuantity']);
    Route::delete('users/cart/remove', [CartController::class, 'removeProductFromCart']);
    Route::delete('users/cart/clear', [CartController::class, 'clearCart']);

    //Order
    Route::get('users/orders/my-order-by-status', [CommandeController::class, 'getMyCommandesByStatus']);
    Route::get('users/orders/my-orders', [CommandeController::class, 'getMyCommandes']);
    Route::post('users/orders/create', [CommandeController::class, 'addCommande']);
    Route::get('users/orders/{id}', [CommandeController::class, 'getCommande']);
    Route::delete('users/orders/cancel/{id}', [CommandeController::class, 'deleteCommande']);

    //Deconnexion
    Route::post('auth/logout', [AuthController::class, 'logout']);
   
});



//================================================================================================
//Les routes accessibles uniquement pour les utilisateurs ayant le rôle 'admin'
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    //Users
    Route::get('admins/users', [UserController::class, 'getAllUsers']);
    Route::get('admins/users/{id}', [UserController::class, 'getUser']);
    Route::delete('admins/users', [UserController::class, 'deleteUser']);


    //Products
    Route::post('admins/products', [ProductController::class, 'createProduct']);
    Route::put('admins/products/{id}', [ProductController::class, 'updateProduct']);
    Route::delete('admins/products/{id}', [ProductController::class, 'deleteProduct']);

    //cart   
    Route::get('admins/carts/all', [CartController::class, 'getAllCarts']);
    Route::get('admins/carts/{id}', [CartController::class, 'getCart']);
    //Order
    Route::put('admins/orders/update/{id}', [CommandeController::class, 'updateCommande']);
    Route::delete('admins/orders/delete/{id}', [CommandeController::class, 'deleteCommande']);
    Route::get('admins/orders/all', [CommandeController::class, 'getAllCommandes']);
    Route::get('admins/orders/{id}', [CommandeController::class, 'getCommande']);
    Route::get('admins/orders/by-user/{id}', [CommandeController::class, 'getCommandesByUser']);
    Route::get('admins/orders/by-status/{status}', [CommandeController::class, 'getCommandesByStatus']);
    

});





//productRoute//
// Route::get("product-list", [ProductController::class, 'producteListe']);
// Route::get("product/{id}" , [ProductController::class, 'getProduct']);
// Route::post("ajouter-product" , [ProductController::class, 'ajouterProduct']);
// Route::put("modifier-product/{id}" , [ProductController::class, 'updateProduct']);
// Route::delete("suprimer-product/{id}" , [ProductController::class, 'suprimerProduct']);

// //categori//

// Route::get("categorie-list", [categorietController::class, 'categorieListe']);
// Route::get("categorie/{id}" , [categorietController::class, 'getCategorie']);
// Route::post("ajouter-categorie" , [categorietController::class, 'ajouterCategorie']);
// Route::put("modifier-categorie/{id}" , [categorietController::class, 'updateCategorie']);
// Route::delete("suprimer-categorie/{id}" , [categorietController::class, 'suprimerCategorie']);


// //user rout//
// Route::get("users-list", [AuthController::class, 'userListe']);
// Route::get("users/{id}" , [AuthController::class, 'getUser']);
// Route::post("register" , [AuthController::class, 'ajouterUser']);
// Route::put("modifier-user/{id}" , [AuthController::class, 'updateUser']);
// Route::post("login", [AuthController::class, 'login']);
// Route::delete("suprimer-user/{id}" , [AuthController::class, 'suprimerUser']);


// // ORDER//
// Route::get("order-list", [OrderController::class, 'orderListe']);
// Route::get("order/{orderId}" , [OrderController::class, 'getOrder']);
// Route::post("create_order" , [OrderController::class, 'ajouterOrder']);
// Route::put("modifier-order/{orderId}" , [OrderController::class, 'updateOrder']);
// Route::put("orders/{orderId}/status", [OrderController::class, 'updateOrderStatus']);
// Route::delete("suprimer-order/{orderId}" , [OrderController::class, 'suprimerOrder']);
 
// //CARTS//
// Route::get("cart-list", [ CartController::class, 'cartListe']);
// Route::get("cart/{cartId}" , [ CartController::class, 'gecart']);
// Route::post("create_cart" , [ CartController::class, 'ajouterCart']);
// Route::put("modifier-cart/{cartId}" , [ CartController::class, 'updateCart']);
// Route::delete("suprimer-cart/{cartId}" , [ CartController::class, 'suprimerCart']);
 