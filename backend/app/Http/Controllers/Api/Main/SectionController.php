<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\JsonResponse;

class SectionController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $row = Section::find(1);
        return response()->json($row ? $row->toArray() : []);
    }
}
