<?php

use App\Http\Controllers\Api\Admin\ArticleController;
use Illuminate\Support\Facades\Route;

Route::get('articles/posts', [ArticleController::class, 'index']);
Route::get('articles/post', [ArticleController::class, 'create']);
Route::post('articles/post', [ArticleController::class, 'store']);
Route::get('articles/post/{id}', [ArticleController::class, 'show']);
Route::put('articles/post/{id}', [ArticleController::class, 'update']);
Route::delete('articles/post/{id}', [ArticleController::class, 'destroy']);
Route::get('articles/posts/order-up/{id}', [ArticleController::class, 'orderUp']);
Route::get('articles/posts/order-down/{id}', [ArticleController::class, 'orderDown']);
