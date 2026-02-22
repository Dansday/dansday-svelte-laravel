<?php

use App\Http\Controllers\Api\Admin\SummernoteUploadController;
use Illuminate\Support\Facades\Route;

Route::post('summernote/upload', SummernoteUploadController::class);
