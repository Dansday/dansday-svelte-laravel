<?php

use App\Http\Controllers\Api\Admin\SectionController;
use Illuminate\Support\Facades\Route;

Route::get('sections', [SectionController::class, 'index']);
Route::put('sections', [SectionController::class, 'update']);
