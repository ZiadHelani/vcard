<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PlanResource;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function getAllPlans(): \Illuminate\Http\JsonResponse
    {
        $plans = Plan::query()->get();
        return response()->json([
            'plans' => PlanResource::collection($plans),
        ]);
    }

    public function show(Plan $plan): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'plan' => PlanResource::make($plan),
        ]);
    }

}
