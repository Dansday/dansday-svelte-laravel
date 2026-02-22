<?php

use App\Http\Controllers\Api\Admin\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('profile', [ProfileController::class, 'index']);
Route::put('profile', [ProfileController::class, 'update']);
