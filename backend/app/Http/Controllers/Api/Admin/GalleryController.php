<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Gallery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{
    public function index(int $id): JsonResponse
    {
        $gallery_images = Gallery::where('article_id', $id)->get();
        $post = Article::find($id);
        if (! $post) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json(['gallery_images' => $gallery_images, 'post' => $post]);
    }

    public function store(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'gallery_image' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:10240'],
            'post_id' => ['required', 'integer', 'exists:articles,id'],
        ]);
        if ($validate->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validate->errors()], 422);
        }
        $dir = public_path('uploads/img/articles');
        File::ensureDirectoryExists($dir);
        $name = 'gallery_image_' . mt_rand(100, 9999) . '.' . $request->file('gallery_image')->guessExtension();
        $request->file('gallery_image')->move($dir, $name);
        $path = 'uploads/img/articles/' . $name;
        Gallery::create(['image' => $path, 'article_id' => $request->input('post_id')]);
        return response()->json(['message' => 'ok', 'image' => $path], 201);
    }

    public function destroy(int $id, Request $request): JsonResponse
    {
        $item = Gallery::find($id);
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
