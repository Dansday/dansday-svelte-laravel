<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\Service;
use App\Models\Skill;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;

class AboutsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $design_skills = Skill::where('type', 'design')->orderBy('order')->get()->toArray();
        $dev_skills = Skill::where('type', 'development')->orderBy('order')->get()->toArray();
        $edu_experiences = Experience::where('type', 'education')->orderBy('order')->get()->toArray();
        $emp_experiences = Experience::where('type', 'employment')->orderBy('order')->get()->toArray();
        $testimonials = Testimonial::orderBy('order')->get()->toArray();
        $services = Service::orderBy('order')->get()->toArray();

        return response()->json([
            'design_skills' => $design_skills,
            'dev_skills' => $dev_skills,
            'edu_experiences' => $edu_experiences,
            'emp_experiences' => $emp_experiences,
            'testimonials' => $testimonials,
            'services' => $services,
        ]);
    }
}
