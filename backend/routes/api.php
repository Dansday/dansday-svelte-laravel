<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Main API (public - frontend main site)
|--------------------------------------------------------------------------
*/
require __DIR__.'/api/main/general.php';
require __DIR__.'/api/main/home.php';
require __DIR__.'/api/main/section.php';
require __DIR__.'/api/main/abouts.php';
require __DIR__.'/api/main/articles.php';
require __DIR__.'/api/main/projects.php';

/*
|--------------------------------------------------------------------------
| Auth (login, register, logout, user - no prefix)
|--------------------------------------------------------------------------
*/
require __DIR__.'/api/admin/auth.php';

/*
|--------------------------------------------------------------------------
| Admin API (authenticated - frontend admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth:sanctum', \App\Http\Middleware\XSS::class])->group(function () {
    require __DIR__.'/api/admin/home.php';
    require __DIR__.'/api/admin/upload.php';
    require __DIR__.'/api/admin/skills.php';
    require __DIR__.'/api/admin/experiences.php';
    require __DIR__.'/api/admin/testimonials.php';
    require __DIR__.'/api/admin/services.php';
    require __DIR__.'/api/admin/sections.php';
    require __DIR__.'/api/admin/general.php';
    require __DIR__.'/api/admin/profile.php';
    require __DIR__.'/api/admin/article-categories.php';
    require __DIR__.'/api/admin/articles.php';
    require __DIR__.'/api/admin/article-gallery.php';
    require __DIR__.'/api/admin/project-categories.php';
    require __DIR__.'/api/admin/projects.php';
    require __DIR__.'/api/admin/project-gallery.php';
});
