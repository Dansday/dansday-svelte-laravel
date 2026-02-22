<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use App\Models\General;
use Illuminate\Http\JsonResponse;

class GeneralController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $row = General::find(1);
        return response()->json($row ? $row->toArray() : []);
    }
}
