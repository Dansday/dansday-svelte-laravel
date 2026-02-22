<?php

use App\Http\Controllers\Api\Admin\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('home', [HomeController::class, 'index']);
Route::put('home', [HomeController::class, 'update']);
