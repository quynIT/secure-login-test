<?php

use App\Http\Controllers\FoodController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use App\Http\Middleware\CustomAuth;
use App\Models\Foods;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/product', [ProductController::class, 'product']);
// Route::get('/post', [PostController::class, 'post']);
// Route::get('/foods', [FoodController::class, 'food'])->name('foods.index'); 
Route::post('/foods', [FoodController::class, 'store'])->name('foods.store'); 
Route::get('/foods/edit/{id}', [FoodController::class, 'edit'])->name('foods.edit'); 
Route::post('/foods/update/{id}', [FoodController::class, 'update'])->name('foods.update'); 
Route::delete('/foods/delete/{id}', [FoodController::class, 'delete'])->name('foods.delete');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');


// Trang đăng nhập
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::middleware([CustomAuth::class])->group(function () {
    Route::get('/post', [PostController::class, 'post']);
    Route::get('/foods', [FoodController::class, 'food'])->name('foods.index'); 
});