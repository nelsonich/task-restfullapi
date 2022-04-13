<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


Route::controller(CategoryController::class)->prefix('categories')->group(function () {
    Route::post('/create', 'create');
    Route::post('{id}/delete', 'destroy');
});

Route::controller(ProductController::class)->prefix('products')->group(function () {
    Route::post('/create', 'create');
    Route::put('{id}/edit', 'edit');
    Route::delete('{id}/delete', 'remove');
    Route::post('/filter', 'filter');
});
