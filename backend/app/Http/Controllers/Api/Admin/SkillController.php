<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SkillController extends Controller
{
    public function index(): JsonResponse
    {
        $design_skills = Skill::where('type', 'design')->orderBy('order')->get();
        $dev_skills = Skill::where('type', 'development')->orderBy('order')->get();
        return response()->json(['design_skills' => $design_skills, 'dev_skills' => $dev_skills]);
    }

    public function store(Request $request): JsonResponse
    {
        $type = $request->input('type');
        $order = $type === 'design' ? (int) $request->input('order_design') : (int) $request->input('order_dev');
        $validate = Validator::make($request->only('title'), ['title' => ['required', 'string', 'max:55']]);
        if ($validate->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validate->errors()], 422);
        }
        Skill::create(['type' => $type, 'title' => $request->input('title'), 'order' => $order]);
        return response()->json(['message' => 'ok'], 201);
    }

    public function show(int $id): JsonResponse
    {
        $skill = Skill::find($id);
        if (! $skill) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($skill);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validate = Validator::make($request->only('type', 'title', 'order'), [
            'title' => ['required', 'string', 'max:55'],
        ]);
        if ($validate->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validate->errors()], 422);
        }
        $skill = Skill::find($id);
        if (! $skill) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $skill->update($request->only('type', 'title', 'order'));
        return response()->json(['message' => 'ok']);
    }

    public function destroy(int $id): JsonResponse
    {
        $skill = Skill::find($id);
        if (! $skill) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $type = $skill->type;
        $skill->delete();
        $skills = Skill::where('type', $type)->orderBy('order')->get();
        foreach ($skills as $i => $s) {
            $s->update(['order' => $i + 1]);
        }
        return response()->json(['message' => 'ok']);
    }

    public function orderUp(int $id): JsonResponse
    {
        $skill1 = Skill::find($id);
        if (! $skill1) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $skill2 = Skill::where('type', $skill1->type)->where('order', $skill1->order - 1)->first();
        if (! $skill2) {
            return response()->json(['message' => 'ok']);
        }
        $o1 = $skill1->order;
        $o2 = $skill2->order;
        $skill1->update(['order' => $o2]);
        $skill2->update(['order' => $o1]);
        return response()->json(['message' => 'ok']);
    }

    public function orderDown(int $id): JsonResponse
    {
        $skill1 = Skill::find($id);
        if (! $skill1) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $skill2 = Skill::where('type', $skill1->type)->where('order', $skill1->order + 1)->first();
        if (! $skill2) {
            return response()->json(['message' => 'ok']);
        }
        $o1 = $skill1->order;
        $o2 = $skill2->order;
        $skill1->update(['order' => $o2]);
        $skill2->update(['order' => $o1]);
        return response()->json(['message' => 'ok']);
    }
}
