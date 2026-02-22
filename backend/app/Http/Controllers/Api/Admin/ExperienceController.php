<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExperienceController extends Controller
{
    public function index(): JsonResponse
    {
        $edu = Experience::where('type', 'education')->orderBy('order')->get();
        $emp = Experience::where('type', 'employment')->orderBy('order')->get();
        return response()->json(['edu_experiences' => $edu, 'emp_experiences' => $emp]);
    }

    public function store(Request $request): JsonResponse
    {
        $type = $request->input('type');
        $order = $type === 'education' ? (int) $request->input('order_edu') : (int) $request->input('order_emp');
        $validate = Validator::make($request->only('title', 'period', 'description'), [
            'title' => ['required', 'string', 'max:55'],
            'period' => ['required', 'string', 'max:55'],
            'description' => ['required', 'string', 'max:255'],
        ]);
        if ($validate->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validate->errors()], 422);
        }
        Experience::create([
            'type' => $type,
            'title' => $request->input('title'),
            'period' => $request->input('period'),
            'description' => $request->input('description'),
            'order' => $order,
        ]);
        return response()->json(['message' => 'ok'], 201);
    }

    public function show(int $id): JsonResponse
    {
        $item = Experience::find($id);
        if (! $item) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($item);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validate = Validator::make($request->only('title', 'period', 'description'), [
            'title' => ['required', 'string', 'max:55'],
            'period' => ['required', 'string', 'max:55'],
            'description' => ['required', 'string', 'max:255'],
        ]);
        if ($validate->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validate->errors()], 422);
        }
        $item = Experience::find($id);
        if (! $item) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $item->update($request->only('type', 'title', 'period', 'description', 'order'));
        return response()->json(['message' => 'ok']);
    }

    public function destroy(int $id): JsonResponse
    {
        $item = Experience::find($id);
        if (! $item) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $type = $item->type;
        $item->delete();
        $items = Experience::where('type', $type)->orderBy('order')->get();
        foreach ($items as $i => $s) {
            $s->update(['order' => $i + 1]);
        }
        return response()->json(['message' => 'ok']);
    }

    public function orderUp(int $id): JsonResponse
    {
        $a = Experience::find($id);
        if (! $a) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $b = Experience::where('type', $a->type)->where('order', $a->order - 1)->first();
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
        $a = Experience::find($id);
        if (! $a) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $b = Experience::where('type', $a->type)->where('order', $a->order + 1)->first();
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
