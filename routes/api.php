<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me',      [AuthController::class, 'me']);
    });
});

//Public routes
Route::get('/categories',[CategoryController::class, 'index']);
Route::get('/categories/{category}',[CategoryController::class, 'show']);
// Get approved comments for a recipe (paginated, nested replies)
Route::get('/recipes/{slug}/comments', [CommentController::class, 'index']);
// Post a comment (guest or authenticated user)
Route::post('/comments', [CommentController::class, 'store']);

Route::get('/recipes',[RecipeController::class,'index']);
Route::get('/recipes/{slug}',[RecipeController::class,'show']);

//Admin routes
Route::middleware(['auth:sanctum','role:admin'])->group(function(){

    //Dashboard
    Route::get('/dashboard',[DashboardController::class, 'index']);

    //Categories
    Route::post('/categories',[CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    //Comments
   // List all comments (filter by ?status=pending|approved|rejected)
    Route::get('/comments',             [CommentController::class, 'adminIndex']);
    // Approve / reject / set back to pending
    Route::patch('/comments/{comment}', [CommentController::class, 'updateStatus']);
    // Delete comment + its replies
    Route::delete('/comments/{comment}',[CommentController::class, 'destroy']);

    //Recipes
    Route::post('/recipes',[RecipeController::class,'store']);
    Route::patch('/recipes/{recipe}',[RecipeController::class,'update']);
    Route::delete('/recipes/{recipe}',[RecipeController::class,'delete']);

    //Users
    Route::get('/users',[UserController::class, 'index']);
    Route::get('/users/{user}',[UserController::class,'show']);
    Route::post('/users',[UserController::class, 'store']);
    Route::patch('/users/{user}',[UserController::class,'update']);
    

});