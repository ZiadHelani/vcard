<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreSupportRequest;
use App\Models\Support;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function store(StoreSupportRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::guard('sanctum')->user();
        $user?->supports()->create($request->validated());
        return response()->json([
            'message' => 'Support created successfully',
        ]);
    }
}
