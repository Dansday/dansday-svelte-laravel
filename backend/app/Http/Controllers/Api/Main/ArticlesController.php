<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
class ArticlesController extends Controller
{
    public function index(): JsonResponse
    {
        $rows = Article::where('status', 'published')->orderBy('order')->get();
        return response()->json($rows->toArray());
    }

    public function show(string $slug): JsonResponse
    {
        $post = Article::where('slug', $slug)->first();
        if (! $post) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $data = $post->toArray();
        $category = Category::find($post->category_id);
        $data['category_name'] = $category?->name;
        $data['date_formated'] = $post->created_at
            ? $post->created_at->format('M j, Y')
            : null;
        return response()->json($data);
    }
}
