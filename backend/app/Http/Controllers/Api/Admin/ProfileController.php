<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index(): JsonResponse
    {
        $user = User::find(1);
        if (! $user) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($user->only('id', 'name', 'email', 'image'));
    }

    public function update(Request $request): JsonResponse
    {
        $user = User::find(1);
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'image_profile_current' => $request->input('image_profile_current'),
            'pass_current' => $request->input('pass_current'),
            'pass_new_1' => $request->input('pass_new_1'),
            'pass_new_2' => $request->input('pass_new_2'),
        ];
        $validate = Validator::make($data, [
            'name' => ['nullable', 'string', 'max:55'],
            'email' => ['nullable', 'email', 'max:55'],
        ]);
        if ($validate->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validate->errors()], 422);
        }
        $route_image = $data['image_profile_current'] ?? $user->image;
        if ($request->hasFile('image_profile')) {
            if ($route_image) {
                $oldPath = public_path($route_image);
                if (File::exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $dir = 'uploads/img/profile';
            File::ensureDirectoryExists(public_path($dir));
            $name = 'profile_image_' . mt_rand(100, 999) . '.' . $request->file('image_profile')->guessExtension();
            $request->file('image_profile')->move(public_path($dir), $name);
            $route_image = $dir . '/' . $name;
        }
        $data_new = ['name' => $data['name'], 'email' => $data['email'], 'image' => $route_image];
        if (! empty($data['pass_current']) || ! empty($data['pass_new_1']) || ! empty($data['pass_new_2'])) {
            $validate = Validator::make($data, [
                'pass_current' => ['required'],
                'pass_new_1' => ['required', 'string', 'min:8'],
                'pass_new_2' => ['required', 'string', 'min:8', 'same:pass_new_1'],
            ]);
            if ($validate->fails()) {
                return response()->json(['message' => 'Validation failed', 'errors' => $validate->errors()], 422);
            }
            if (! Hash::check($data['pass_current'], $user->password)) {
                return response()->json(['message' => 'Current password is incorrect'], 422);
            }
            $data_new['password'] = Hash::make($data['pass_new_1']);
        }
        $user->update($data_new);
        return response()->json(['message' => 'ok']);
    }
}
