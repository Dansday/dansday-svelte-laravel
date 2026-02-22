<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index(): JsonResponse
    {
        $section = Section::find(1);
        if (! $section) {
            return response()->json(['message' => 'Initial data not found. Run: php artisan db:seed'], 404);
        }
        return response()->json($section);
    }

    public function update(Request $request): JsonResponse
    {
        Section::where('id', 1)->update([
            'about_enable' => $request->boolean('about_enable'),
            'about_experience_order' => (int) $request->input('about_experience_order', 0),
            'about_services_order' => (int) $request->input('about_services_order', 0),
            'about_skills_order' => (int) $request->input('about_skills_order', 0),
            'about_testimonial_order' => (int) $request->input('about_testimonial_order', 0),
            'experience_enable' => $request->boolean('experience_enable'),
            'skills_enable' => $request->boolean('skills_enable'),
            'testimonial_enable' => $request->boolean('testimonial_enable'),
            'services_enable' => $request->boolean('services_enable'),
            'projects_enable' => $request->boolean('projects_enable'),
            'articles_enable' => $request->boolean('articles_enable'),
        ]);
        return response()->json(['message' => 'ok']);
    }
}
