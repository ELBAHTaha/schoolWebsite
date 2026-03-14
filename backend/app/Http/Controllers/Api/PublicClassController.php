<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use Illuminate\Http\JsonResponse;

class PublicClassController extends Controller
{
    public function index(): JsonResponse
    {
        $classes = SchoolClass::query()
            ->select(['id', 'name', 'description', 'price_1_month', 'price_3_month', 'price_6_month'])
            ->orderBy('name')
            ->get();

        return response()->json([
            'data' => $classes,
        ]);
    }
}
