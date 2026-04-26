<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PrivacyContentResource;
use App\Models\PrivacyContent;
use Illuminate\Http\Request;

class PrivacyContentController extends Controller
{
    public function getPrivacyContent(): \Illuminate\Http\JsonResponse
    {
        $privacyContent = PrivacyContent::query()->first();
        return response()->json([
            'success' => true,
            'data' => PrivacyContentResource::make($privacyContent),
            'message' => 'Fetch Privacy Content Successfully',
        ]);
    }
}
