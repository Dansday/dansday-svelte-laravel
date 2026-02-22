<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Http\JsonResponse;

class ProjectsController extends Controller
{
    public function index(): JsonResponse
    {
        $projects = Project::where('enable', 1)->orderBy('order')->get()->toArray();
        $projects_categories = ProjectCategory::orderBy('id')->get()->toArray();
        return response()->json([
            'projects' => $projects,
            'projects_categories' => $projects_categories,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $project = Project::find($id);
        if (! $project) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $data = $project->toArray();
        $category = ProjectCategory::find($project->category_id);
        $data['category_name'] = $category?->name;
        return response()->json($data);
    }
}
