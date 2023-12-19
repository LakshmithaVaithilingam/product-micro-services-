<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SubcategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReviewController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('category', [CategoryController::class, 'index']);
Route::post('category', [CategoryController::class, 'store']);
Route::get('category/{id}', [CategoryController::class, 'show']);
Route::get('category/{id}/edit', [CategoryController::class, 'edit']);
Route::put('category/{id}/edit', [CategoryController::class, 'update']);
Route::delete('category/{id}/delete', [CategoryController::class, 'destroy']);

//subcategories
Route::get('subcategory', [SubcategoryController::class, 'index']);
Route::post('subcategory', [SubcategoryController::class, 'store']);
Route::get('subcategory/{id}', [SubcategoryController::class, 'show']);
Route::get('subcategory/{id}/edit', [SubcategoryController::class, 'edit']);
Route::put('subcategory/{id}/edit', [SubcategoryController::class, 'update']);
Route::delete('subcategory/{id}/delete', [SubcategoryController::class, 'destroy']);
Route::get('category/{categoryId}/subcategories', [SubcategoryController::class, 'getSubcategoriesByCategory']);



//products 
Route::get('products', [ProductController::class, 'index']);
Route::post('products', [ProductController::class, 'store']);
Route::get('products/{id}', [ProductController::class, 'show']);
Route::get('products/{id}/edit', [ProductController::class, 'edit']);
Route::put('products/{id}/edit', [ProductController::class, 'update']);
Route::delete('products/{id}/delete', [ProductController::class, 'destroy']);
Route::get('related-products/{categoryId}/{selectedProductId}', [ProductController::class, 'getRelatedProducts']);

// Retrieve reviews for a specific product
Route::get('reviews/{productId}', [ReviewController::class, 'index']);
// Store a new review
Route::post('reviews', [ReviewController::class, 'store']);
// Retrieve a specific review
Route::get('reviews/{reviewId}', [ReviewController::class, 'show']);
// Update a specific review
Route::put('reviews/{reviewId}', [ReviewController::class, 'update']);
// Delete a specific review
Route::delete('reviews/{reviewId}', [ReviewController::class, 'destroy']);