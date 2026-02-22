<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Home;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index(): JsonResponse
    {
        $home = Home::find(1);
        if (! $home) {
            return response()->json(['message' => 'Initial data not found. Run: php artisan db:seed'], 404);
        }
        return response()->json($home);
    }

    public function update(Request $request): JsonResponse
    {
        $data = [
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ];
        $validate = Validator::make($data, [
            'title' => ['required', 'string', 'max:75'],
            'description' => ['nullable', 'string', 'max:5000'],
        ]);
        if ($validate->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validate->errors()], 422);
        }
        Home::where('id', 1)->update($data);
        return response()->json(['message' => 'ok']);
    }
}
