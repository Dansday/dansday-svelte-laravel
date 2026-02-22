<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use App\Models\Home;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $row = Home::find(1);
        $data = $row ? $row->toArray() : ['title' => null, 'description' => null];
        return response()->json($data);
    }
}
