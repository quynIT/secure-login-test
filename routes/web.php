<?php

use App\Http\Controllers\FoodController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Models\Foods;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/product', [ProductController::class, 'product']);
Route::get('/post', [PostController::class, 'post']);
Route::get('/foods', [FoodController::class, 'food'])->name('foods.index'); 
Route::post('/foods', [FoodController::class, 'store'])->name('foods.store'); 
Route::get('/foods/edit/{id}', [FoodController::class, 'edit'])->name('foods.edit'); 
Route::post('/foods/update/{id}', [FoodController::class, 'update'])->name('foods.update'); 
Route::delete('/foods/delete/{id}', [FoodController::class, 'delete'])->name('foods.delete');