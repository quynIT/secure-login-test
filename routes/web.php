<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Models\Foods;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/product', [ProductController::class, 'product']);
Route::get('/post', [PostController::class, 'post']);
Route::resource('/foods', Foods::class);