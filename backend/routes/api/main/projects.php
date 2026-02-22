<?php

use App\Http\Controllers\Api\Main\ProjectsController;
use Illuminate\Support\Facades\Route;

Route::get('projects', [ProjectsController::class, 'index']);
Route::get('projects/{id}', [ProjectsController::class, 'show'])->whereNumber('id');
