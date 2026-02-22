<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Testimonial::orderBy('order')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validate = Validator::make($request->only('name', 'company', 'text'), [
            'name' => ['required', 'string', 'max:55'],
            'company' => ['required', 'string', 'max:55'],
            'text' => ['required', 'string', 'max:255'],
        ]);
        if ($validate->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validate->errors()], 422);
        }
        Testimonial::create([
            'name' => $request->input('name'),
            'company' => $request->input('company'),
            'text' => $request->input('text'),
            'order' => (int) $request->input('order', 0),
        ]);
        return response()->json(['message' => 'ok'], 201);
    }

    public function show(int $id): JsonResponse
    {
        $item = Testimonial::find($id);
        if (! $item) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($item);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validate = Validator::make($request->only('name', 'company', 'text'), [
            'name' => ['required', 'string', 'max:55'],
            'company' => ['required', 'string', 'max:55'],
            'text' => ['required', 'string', 'max:255'],
        ]);
        if ($validate->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validate->errors()], 422);
        }
        $item = Testimonial::find($id);
        if (! $item) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $item->update($request->only('name', 'company', 'text', 'order'));
        return response()->json(['message' => 'ok']);
    }

    public function destroy(int $id): JsonResponse
    {
        $item = Testimonial::find($id);
        if (! $item) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $item->delete();
        $items = Testimonial::orderBy('order')->get();
        foreach ($items as $i => $s) {
            $s->update(['order' => $i + 1]);
        }
        return response()->json(['message' => 'ok']);
    }

    public function orderUp(int $id): JsonResponse
    {
        $a = Testimonial::find($id);
        if (! $a) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $b = Testimonial::where('order', $a->order - 1)->first();
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
        $a = Testimonial::find($id);
        if (! $a) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $b = Testimonial::where('order', $a->order + 1)->first();
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
