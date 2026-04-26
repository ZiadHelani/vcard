<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ActivityResource;
use App\Http\Resources\Api\CardResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomePageController extends Controller
{
    public function getHomePageData()
    {
        $user = Auth::guard('sanctum')->user();
        $cardsCount = $user?->cards()->count();
        $totalViewsCount = $user?->cards()->sum('total_views');
        $contactsCount = $user?->contacts()->count();
        $sharesCount = $user?->cards()->sum('total_saves');
        $cards = $user?->cards()
            ->with('personalDetails')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
        $activities = $user?->activites()->orderBy('created_at', 'desc')->take(5)->get();
        return response()->json([
            'cards_count' => $cardsCount,
            'total_views_count' => $totalViewsCount,
            'contacts_count' => $contactsCount,
            'shares_count' => $sharesCount,
            'cards' => CardResource::collection($cards),
            'activities' => ActivityResource::collection($activities),
        ]);

    }
}
