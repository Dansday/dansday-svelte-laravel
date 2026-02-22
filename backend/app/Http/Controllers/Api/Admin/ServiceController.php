<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Service::orderBy('order')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validate = Validator::make($request->only('title', 'description', 'info'), [
            'title' => ['required', 'string', 'max:55'],
            'description' => ['required', 'string', 'max:255'],
            'info' => ['required', 'string', 'max:510'],
        ]);
        if ($validate->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validate->errors()], 422);
        }
        Service::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'info' => $request->input('info'),
            'order' => (int) $request->input('order', 0),
        ]);
        return response()->json(['message' => 'ok'], 201);
    }

    public function show(int $id): JsonResponse
    {
        $item = Service::find($id);
        if (! $item) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($item);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validate = Validator::make($request->only('title', 'description', 'info'), [
            'title' => ['required', 'string', 'max:55'],
            'description' => ['required', 'string', 'max:255'],
            'info' => ['required', 'string', 'max:510'],
        ]);
        if ($validate->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validate->errors()], 422);
        }
        $item = Service::find($id);
        if (! $item) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $item->update($request->only('title', 'description', 'order', 'info'));
        return response()->json(['message' => 'ok']);
    }

    public function destroy(int $id): JsonResponse
    {
        $item = Service::find($id);
        if (! $item) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $item->delete();
        $items = Service::orderBy('order')->get();
        foreach ($items as $i => $s) {
            $s->update(['order' => $i + 1]);
        }
        return response()->json(['message' => 'ok']);
    }

    public function orderUp(int $id): JsonResponse
    {
        $a = Service::find($id);
        if (! $a) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $b = Service::where('order', $a->order - 1)->first();
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
        $a = Service::find($id);
        if (! $a) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $b = Service::where('order', $a->order + 1)->first();
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
