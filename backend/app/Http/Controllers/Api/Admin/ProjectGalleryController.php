<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectGallery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProjectGalleryController extends Controller
{
    public function index(int $id): JsonResponse
    {
        $gallery_images = ProjectGallery::where('project_id', $id)->get();
        $project = Project::find($id);
        if (! $project) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json(['gallery_images' => $gallery_images, 'project' => $project]);
    }

    public function store(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'gallery_image' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:10240'],
            'project_id' => ['required', 'integer', 'exists:project,id'],
        ]);
        if ($validate->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validate->errors()], 422);
        }
        $dir = public_path('uploads/img/projects');
        File::ensureDirectoryExists($dir);
        $name = 'project_image_' . mt_rand(100, 9999) . '.' . $request->file('gallery_image')->guessExtension();
        $request->file('gallery_image')->move($dir, $name);
        $path = 'uploads/img/projects/' . $name;
        ProjectGallery::create(['image' => $path, 'project_id' => $request->input('project_id')]);
        return response()->json(['message' => 'ok', 'image' => $path], 201);
    }

    public function destroy(int $id): JsonResponse
    {
        $item = ProjectGallery::find($id);
        if (! $item) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $imgPath = public_path($item->image);
        if (File::exists($imgPath)) {
            unlink($imgPath);
        }
        $item->delete();
        return response()->json(['message' => 'ok']);
    }
}
