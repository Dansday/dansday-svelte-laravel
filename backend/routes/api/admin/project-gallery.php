<?php

use App\Http\Controllers\Api\Admin\ProjectGalleryController;
use Illuminate\Support\Facades\Route;

Route::get('projects/gallery/{id}', [ProjectGalleryController::class, 'index']);
Route::post('projects/gallery', [ProjectGalleryController::class, 'store']);
Route::delete('projects/gallery/{id}', [ProjectGalleryController::class, 'destroy']);
