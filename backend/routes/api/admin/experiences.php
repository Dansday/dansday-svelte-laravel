<?php

use App\Http\Controllers\Api\Admin\ExperienceController;
use Illuminate\Support\Facades\Route;

Route::get('experiences', [ExperienceController::class, 'index']);
Route::post('experiences', [ExperienceController::class, 'store']);
Route::get('experiences/{id}', [ExperienceController::class, 'show']);
Route::put('experiences/{id}', [ExperienceController::class, 'update']);
Route::delete('experiences/{id}', [ExperienceController::class, 'destroy']);
Route::get('experiences/order-up/{id}', [ExperienceController::class, 'orderUp']);
Route::get('experiences/order-down/{id}', [ExperienceController::class, 'orderDown']);
