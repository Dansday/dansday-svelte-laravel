<?php

use App\Http\Controllers\Api\Admin\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('projects/projects', [ProjectController::class, 'index']);
Route::get('projects/project', [ProjectController::class, 'create']);
Route::post('projects/project', [ProjectController::class, 'store']);
Route::get('projects/project/{id}', [ProjectController::class, 'show']);
Route::put('projects/project/{id}', [ProjectController::class, 'update']);
Route::delete('projects/project/{id}', [ProjectController::class, 'destroy']);
Route::get('projects/projects/order-up/{id}', [ProjectController::class, 'orderUp']);
Route::get('projects/projects/order-down/{id}', [ProjectController::class, 'orderDown']);
