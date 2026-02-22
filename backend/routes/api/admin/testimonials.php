<?php

use App\Http\Controllers\Api\Admin\TestimonialController;
use Illuminate\Support\Facades\Route;

Route::get('testimonials', [TestimonialController::class, 'index']);
Route::post('testimonials', [TestimonialController::class, 'store']);
Route::get('testimonials/{id}', [TestimonialController::class, 'show']);
Route::put('testimonials/{id}', [TestimonialController::class, 'update']);
Route::delete('testimonials/{id}', [TestimonialController::class, 'destroy']);
Route::get('testimonials/order-up/{id}', [TestimonialController::class, 'orderUp']);
Route::get('testimonials/order-down/{id}', [TestimonialController::class, 'orderDown']);
