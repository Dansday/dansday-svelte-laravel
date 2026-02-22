<?php

use App\Http\Controllers\Api\Main\AboutsController;
use Illuminate\Support\Facades\Route;

Route::get('abouts', AboutsController::class);
