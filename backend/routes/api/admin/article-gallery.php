<?php

use App\Http\Controllers\Api\Admin\GalleryController;
use Illuminate\Support\Facades\Route;

Route::get('articles/gallery/{id}', [GalleryController::class, 'index']);
Route::post('articles/gallery', [GalleryController::class, 'store']);
Route::delete('articles/gallery/{id}', [GalleryController::class, 'destroy']);
