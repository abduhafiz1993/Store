<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CatgoriesController;

Route::resource('products', ProductController::class);
Route::resource('categories', CatgoriesController::class);

Route::get('/', function () {
    return view('welcome');
});
