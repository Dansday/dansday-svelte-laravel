<?php

use App\Http\Controllers\Api\Main\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('home', HomeController::class);
