<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// route resource for products
// route resource digunakan untuk mengenerate beberapa kebutuhan CRUD
// jadi ga perlu nulis route manual 1 per 1, misalkan Get, Post, Put
Route::resource('/products', \App\Http\Controllers\ProductController::class);