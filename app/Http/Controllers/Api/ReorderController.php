<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReorderController extends Controller
{
    public function reorder(Request $request, string $model): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'model' => $model,
            'response' => $request->all(),
        ]);
    }
}
