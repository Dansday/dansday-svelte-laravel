<?php

use App\Http\Controllers\Api\Admin\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('services', [ServiceController::class, 'index']);
Route::post('services', [ServiceController::class, 'store']);
Route::get('services/{id}', [ServiceController::class, 'show']);
Route::put('services/{id}', [ServiceController::class, 'update']);
Route::delete('services/{id}', [ServiceController::class, 'destroy']);
Route::get('services/order-up/{id}', [ServiceController::class, 'orderUp']);
Route::get('services/order-down/{id}', [ServiceController::class, 'orderDown']);
