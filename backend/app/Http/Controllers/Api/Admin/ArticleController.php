<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Gallery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function index(): JsonResponse
    {
        $articles = Article::orderBy('order')->get();
        $categories = Category::all();
        return response()->json(['articles' => $articles, 'categories' => $categories]);
    }

    public function create(): JsonResponse
    {
        $categories = Category::all();
        $articles = Article::orderBy('order')->get();
        return response()->json(['categories' => $categories, 'articles' => $articles]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->only('title', 'short_desc', 'author', 'category', 'status', 'images_code', 'text', 'order');
        $data['image'] = $request->file('image');
        $validate = Validator::make($data, [
            'title' => ['required', 'string', 'max:55'],
            'short_desc' => ['nullable', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:55'],
            'category' => ['required'],
            'status' => ['nullable', 'string', 'max:55'],
            'text' => ['required'],
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:10240'],
        ]);
        if ($validate->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validate->errors()], 422);
        }
        $dir = public_path('uploads/img/articles');
        File::ensureDirectoryExists($dir);
        $route_image = 'uploads/img/articles/post_image_' . mt_rand(10, 9999) . '.' . $request->file('image')->guessExtension();
        $request->file('image')->move($dir, basename($route_image));
        $tempFull = public_path('uploads/img/temp');
        foreach (glob($tempFull . '/*') ?: [] as $file) {
            $name = basename($file);
            @copy($file, $dir . '/' . $name);
            if (File::exists($file)) {
                unlink($file);
            }
        }
        $slug = preg_replace('/[^A-Za-z0-9\-]/', '', strtolower(str_replace(' ', '-', $data['title'])));
        Article::create([
            'title' => $data['title'],
            'short_desc' => $data['short_desc'] ?? '',
            'text' => str_replace('uploads/img/temp', 'uploads/img/articles', $data['text']),
            'image' => $route_image,
            'author' => $data['author'] ?? '',
            'slug' => $slug,
            'status' => $data['status'] ?? 'draft',
            'images_code' => $data['images_code'] ?? '',
            'category_id' => $data['category'],
            'order' => (int) ($data['order'] ?? 0),
        ]);
        return response()->json(['message' => 'ok'], 201);
    }

    public function show(int $id): JsonResponse
    {
        $post = Article::find($id);
        if (! $post) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $categories = Category::all();
        return response()->json(['post' => $post, 'categories' => $categories]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $post = Article::find($id);
        if (! $post) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $data = $request->only('title', 'short_desc', 'author', 'category', 'status', 'images_code', 'text', 'slug');
        $data['image'] = $request->file('image');
        $data['image_current'] = $request->input('image_current');
        $validate = Validator::make($data, [
            'title' => ['required', 'string', 'max:55'],
            'text' => ['required'],
        ]);
        if ($validate->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validate->errors()], 422);
        }
        $route_image = $data['image_current'] ?? $post->image;
        if ($request->hasFile('image')) {
            $dir = public_path('uploads/img/articles');
            File::ensureDirectoryExists($dir);
            $route_image = 'uploads/img/articles/post_image_' . mt_rand(10, 9999) . '.' . $request->file('image')->guessExtension();
            $request->file('image')->move($dir, basename($route_image));
        }
        $tempFull = public_path('uploads/img/temp');
        $dirFull = public_path('uploads/img/articles');
        foreach (glob($tempFull . '/*') ?: [] as $file) {
            $name = basename($file);
            @copy($file, $dirFull . '/' . $name);
            if (File::exists($file)) {
                unlink($file);
            }
        }
        $slug = preg_replace('/[^A-Za-z0-9\-]/', '', strtolower(str_replace(' ', '-', $data['slug'] ?? $data['title'])));
        $post->update([
            'title' => $data['title'],
            'short_desc' => $data['short_desc'] ?? '',
            'text' => str_replace('uploads/img/temp', 'uploads/img/articles', $data['text']),
            'image' => $route_image,
            'author' => $data['author'] ?? '',
            'slug' => $slug,
            'status' => $data['status'] ?? 'draft',
            'category_id' => $data['category'],
        ]);
        return response()->json(['message' => 'ok']);
    }

    public function destroy(int $id): JsonResponse
    {
        $post = Article::find($id);
        if (! $post) {
            return response()->json(['message' => 'Not found'], 404);
        }
        if ($post->image && File::exists(public_path($post->image))) {
            unlink(public_path($post->image));
        }
        $post->delete();
        $posts = Article::orderBy('order')->get();
        foreach ($posts as $i => $p) {
            $p->update(['order' => $i + 1]);
        }
        return response()->json(['message' => 'ok']);
    }

    public function orderUp(int $id): JsonResponse
    {
        $a = Article::find($id);
        if (! $a) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $b = Article::where('order', $a->order - 1)->first();
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
        $a = Article::find($id);
        if (! $a) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $b = Article::where('order', $a->order + 1)->first();
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
