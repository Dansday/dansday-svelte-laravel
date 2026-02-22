<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\General;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class GeneralController extends Controller
{
    public function index(): JsonResponse
    {
        $general = General::find(1);
        if (! $general) {
            return response()->json(['message' => 'Initial data not found. Run: php artisan db:seed'], 404);
        }
        return response()->json($general);
    }

    public function update(Request $request): JsonResponse
    {
        $general = General::find(1);
        $data = [
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'analytics_code' => $request->input('analytics_code'),
            'social_links' => $request->input('social_links'),
        ];
        $validate = Validator::make($data, [
            'title' => ['nullable', 'string', 'max:55'],
            'description' => ['nullable', 'string', 'max:255'],
            'analytics_code' => ['nullable', 'string', 'max:55'],
            'social_links' => ['nullable', 'string'],
        ]);
        if ($validate->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validate->errors()], 422);
        }
        $route_image_favicon = $general->image_favicon;
        if ($request->hasFile('image_favicon')) {
            $validate = Validator::make($request->all(), [
                'image_favicon' => ['file', 'mimes:png', 'max:1024', 'dimensions:min_width=155,min_height=155'],
            ]);
            if ($validate->fails()) {
                return response()->json(['message' => 'Validation failed', 'errors' => $validate->errors()], 422);
            }
            $directory = 'uploads/img/general/favicon';
            $directoryFull = public_path($directory);
            if ($route_image_favicon) {
                foreach (glob($directoryFull . '/*') ?: [] as $file) {
                    if (File::exists($file)) {
                        unlink($file);
                    }
                }
            }
            File::ensureDirectoryExists($directoryFull);
            $ext = $request->file('image_favicon')->guessExtension();
            $route_image_favicon = $directory . '/favicon.' . $ext;
            $request->file('image_favicon')->move($directoryFull, 'favicon.' . $ext);
        }
        $general->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'analytics_code' => $data['analytics_code'],
            'social_links' => $data['social_links'],
            'image_favicon' => $route_image_favicon,
        ]);
        return response()->json(['message' => 'ok']);
    }
}
