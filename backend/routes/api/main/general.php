<?php

use App\Http\Controllers\Api\Main\GeneralController;
use Illuminate\Support\Facades\Route;

Route::get('general', GeneralController::class);
