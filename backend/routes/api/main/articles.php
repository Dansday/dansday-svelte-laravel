<?php

use App\Http\Controllers\Api\Main\ArticlesController;
use Illuminate\Support\Facades\Route;

Route::get('articles', [ArticlesController::class, 'index']);
Route::get('articles/{slug}', [ArticlesController::class, 'show']);
