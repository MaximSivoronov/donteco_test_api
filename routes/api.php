<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1/product')->group(function () {
    Route::get('', [\App\Http\Controllers\v1\productController::class, 'getProducts']);
    Route::get('{product}', [\App\Http\Controllers\v1\productController::class, 'getProduct']);
    Route::post('', [\App\Http\Controllers\v1\productController::class, 'storeProduct']);
    Route::patch('{product}', [\App\Http\Controllers\v1\productController::class, 'updateProduct']);
    Route::delete('{product}', [\App\Http\Controllers\v1\productController::class, 'deleteProduct']);
});

Route::prefix('v1/category')->group(function () {
    Route::get('', [\App\Http\Controllers\v1\categoryController::class, 'getCategories']);
    Route::get('{category}', [\App\Http\Controllers\v1\categoryController::class, 'getCategory']);
    Route::post('', [\App\Http\Controllers\v1\categoryController::class, 'storeCategory']);
    Route::patch('{category}', [\App\Http\Controllers\v1\categoryController::class, 'updateCategory']);
    Route::delete('{category}', [\App\Http\Controllers\v1\categoryController::class, 'deleteCategory']);
});
