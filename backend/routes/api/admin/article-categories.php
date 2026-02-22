<?php

use App\Http\Controllers\Api\Admin\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('articles/categories', [CategoryController::class, 'index']);
Route::post('articles/categories', [CategoryController::class, 'store']);
Route::get('articles/categories/{id}', [CategoryController::class, 'show']);
Route::put('articles/categories/{id}', [CategoryController::class, 'update']);
Route::delete('articles/categories/{id}', [CategoryController::class, 'destroy']);
