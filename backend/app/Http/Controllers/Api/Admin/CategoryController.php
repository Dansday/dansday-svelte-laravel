<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Category::all());
    }

    public function store(Request $request): JsonResponse
    {
        $validate = Validator::make($request->only('name'), ['name' => ['required', 'string', 'max:55']]);
        if ($validate->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validate->errors()], 422);
        }
        $name = $request->input('name');
        $slug = Str::slug($name);
        Category::create(['name' => $name, 'slug' => $slug]);
        return response()->json(['message' => 'ok'], 201);
    }

    public function show(int $id): JsonResponse
    {
        $item = Category::find($id);
        if (! $item) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($item);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validate = Validator::make($request->only('name', 'slug'), [
            'name' => ['required', 'string', 'max:55'],
            'slug' => ['required', 'string', 'max:55'],
        ]);
        if ($validate->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validate->errors()], 422);
        }
        $item = Category::find($id);
        if (! $item) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $item->update(['name' => $request->input('name'), 'slug' => Str::slug($request->input('slug'))]);
        return response()->json(['message' => 'ok']);
    }

    public function destroy(int $id): JsonResponse
    {
        $item = Category::find($id);
        if (! $item) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $item->delete();
        return response()->json(['message' => 'ok']);
    }
}
