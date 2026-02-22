<?php

use App\Http\Controllers\Api\Admin\GeneralController;
use Illuminate\Support\Facades\Route;

Route::get('general', [GeneralController::class, 'index']);
Route::put('general', [GeneralController::class, 'update']);
