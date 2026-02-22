<?php

use App\Http\Controllers\Api\Admin\ProjectCategoryController;
use Illuminate\Support\Facades\Route;

Route::get('projects/categories', [ProjectCategoryController::class, 'index']);
Route::post('projects/categories', [ProjectCategoryController::class, 'store']);
Route::put('projects/categories/{id}', [ProjectCategoryController::class, 'update']);
Route::delete('projects/categories/{id}', [ProjectCategoryController::class, 'destroy']);
