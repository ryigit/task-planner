<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class TestController extends Controller
{
    public function providerOne(): JsonResponse
    {
        return response()->json([
            [
                'id' => 1,
                'value' => 4,
                'estimated_duration' => 2,
            ],
            [
                'id' => 2,
                'value' => 3,
                'estimated_duration' => 1,
            ],
            [
                'id' => 3,
                'value' => 5,
                'estimated_duration' => 3,
            ],
        ]);
    }

    public function providerTwo(): JsonResponse
    {
        return response()->json([
            [
                'id' => 1,
                'zorluk' => 4,
                'sure' => 2,
            ],
            [
                'id' => 2,
                'zorluk' => 3,
                'sure' => 1,
            ],
            [
                'id' => 3,
                'zorluk' => 5,
                'sure' => 3,
            ],
        ]);
    }
}
