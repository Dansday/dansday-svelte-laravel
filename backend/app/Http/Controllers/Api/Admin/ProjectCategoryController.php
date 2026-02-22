<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectCategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(ProjectCategory::all());
    }

    public function store(Request $request): JsonResponse
    {
        $validate = Validator::make($request->only('name'), ['name' => ['required', 'string', 'max:55']]);
        if ($validate->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validate->errors()], 422);
        }
        ProjectCategory::create(['name' => $request->input('name')]);
        return response()->json(['message' => 'ok'], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validate = Validator::make($request->only('name'), ['name' => ['required', 'string', 'max:55']]);
        if ($validate->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validate->errors()], 422);
        }
        $item = ProjectCategory::find($id);
        if (! $item) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $item->update(['name' => $request->input('name')]);
        return response()->json(['message' => 'ok']);
    }

    public function destroy(int $id): JsonResponse
    {
        $item = ProjectCategory::find($id);
        if (! $item) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $item->delete();
        return response()->json(['message' => 'ok']);
    }
}
