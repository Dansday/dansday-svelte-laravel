<?php

use App\Http\Controllers\Api\Admin\SkillController;
use Illuminate\Support\Facades\Route;

Route::get('skills', [SkillController::class, 'index']);
Route::post('skills', [SkillController::class, 'store']);
Route::get('skills/{id}', [SkillController::class, 'show']);
Route::put('skills/{id}', [SkillController::class, 'update']);
Route::delete('skills/{id}', [SkillController::class, 'destroy']);
Route::get('skills/order-up/{id}', [SkillController::class, 'orderUp']);
Route::get('skills/order-down/{id}', [SkillController::class, 'orderDown']);
