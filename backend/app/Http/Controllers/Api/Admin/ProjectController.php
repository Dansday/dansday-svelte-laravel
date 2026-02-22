<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index(): JsonResponse
    {
        $projects = Project::orderBy('order')->get();
        $categories = ProjectCategory::all();
        return response()->json(['projects' => $projects, 'categories' => $categories]);
    }

    public function create(): JsonResponse
    {
        $categories = ProjectCategory::all();
        $projects = Project::orderBy('order')->get();
        return response()->json(['categories' => $categories, 'projects' => $projects]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->only('title', 'short_desc', 'description', 'images_code', 'category', 'order', 'enable_project');
        $data['image'] = $request->file('image');
        $validate = Validator::make($data, [
            'title' => ['nullable', 'string', 'max:55'],
            'short_desc' => ['nullable', 'string', 'max:110'],
            'description' => ['required'],
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:10240'],
        ]);
        if ($validate->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validate->errors()], 422);
        }
        $dir = public_path('uploads/img/projects');
        File::ensureDirectoryExists($dir);
        $route_image = 'uploads/img/projects/project_image_' . mt_rand(10, 9999) . '.' . $request->file('image')->guessExtension();
        $request->file('image')->move($dir, basename($route_image));
        $tempFull = public_path('uploads/img/temp');
        foreach (glob($tempFull . '/*') ?: [] as $file) {
            $name = basename($file);
            @copy($file, $dir . '/' . $name);
            if (File::exists($file)) {
                unlink($file);
            }
        }
        $enable = $request->boolean('enable_project') || $request->input('enable_project') === 'on' ? 1 : 0;
        Project::create([
            'enable' => $enable,
            'title' => $request->input('title'),
            'short_desc' => $request->input('short_desc'),
            'images_code' => $request->input('images_code', ''),
            'description' => str_replace('uploads/img/temp', 'uploads/img/projects', $request->input('description')),
            'image' => $route_image,
            'order' => (int) $request->input('order', 0),
            'category_id' => $request->input('category'),
        ]);
        return response()->json(['message' => 'ok'], 201);
    }

    public function show(int $id): JsonResponse
    {
        $project = Project::find($id);
        if (! $project) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $categories = ProjectCategory::all();
        return response()->json(['project' => $project, 'categories' => $categories]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $project = Project::find($id);
        if (! $project) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $validate = Validator::make($request->only('title', 'short_desc', 'description'), [
            'title' => ['required', 'string', 'max:55'],
            'short_desc' => ['nullable', 'string', 'max:110'],
            'description' => ['required'],
        ]);
        if ($validate->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validate->errors()], 422);
        }
        $route_image = $request->input('image_current') ?? $project->image;
        if ($request->hasFile('image')) {
            if ($route_image && File::exists(public_path($route_image))) {
                unlink(public_path($route_image));
            }
            $dir = public_path('uploads/img/projects');
            File::ensureDirectoryExists($dir);
            $route_image = 'uploads/img/projects/project_image_' . mt_rand(10, 9999) . '.' . $request->file('image')->guessExtension();
            $request->file('image')->move($dir, basename($route_image));
        }
        $tempFull = public_path('uploads/img/temp');
        $dirFull = public_path('uploads/img/projects');
        foreach (glob($tempFull . '/*') ?: [] as $file) {
            $name = basename($file);
            @copy($file, $dirFull . '/' . $name);
            if (File::exists($file)) {
                unlink($file);
            }
        }
        $enable = $request->boolean('enable_project') || $request->input('enable_project') === 'on' ? 1 : 0;
        $project->update([
            'enable' => $enable,
            'title' => $request->input('title'),
            'short_desc' => $request->input('short_desc'),
            'description' => str_replace('uploads/img/temp', 'uploads/img/projects', $request->input('description')),
            'image' => $route_image,
            'category_id' => $request->input('category'),
        ]);
        return response()->json(['message' => 'ok']);
    }

    public function destroy(int $id): JsonResponse
    {
        $project = Project::find($id);
        if (! $project) {
            return response()->json(['message' => 'Not found'], 404);
        }
        if ($project->image && File::exists(public_path($project->image))) {
            unlink(public_path($project->image));
        }
        $project->delete();
        $projects = Project::orderBy('order')->get();
        foreach ($projects as $i => $p) {
            $p->update(['order' => $i + 1]);
        }
        return response()->json(['message' => 'ok']);
    }

    public function orderUp(int $id): JsonResponse
    {
        $a = Project::find($id);
        if (! $a) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $b = Project::where('order', $a->order - 1)->first();
        if (! $b) {
            return response()->json(['message' => 'ok']);
        }
        $o1 = $a->order;
        $o2 = $b->order;
        $a->update(['order' => $o2]);
        $b->update(['order' => $o1]);
        return response()->json(['message' => 'ok']);
    }

    public function orderDown(int $id): JsonResponse
    {
        $a = Project::find($id);
        if (! $a) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $b = Project::where('order', $a->order + 1)->first();
        if (! $b) {
            return response()->json(['message' => 'ok']);
        }
        $o1 = $a->order;
        $o2 = $b->order;
        $a->update(['order' => $o2]);
        $b->update(['order' => $o1]);
        return response()->json(['message' => 'ok']);
    }
}
