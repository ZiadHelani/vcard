<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCardRequest;
use App\Http\Requests\Api\StoreCardReviewRequest;
use App\Http\Requests\Api\UpdateCardReviewRequest;
use App\Http\Resources\Api\CardResource;
use App\Models\CardReview;
use Illuminate\Http\Request;

class CardReviewController extends Controller
{
    public function store(StoreCardReviewRequest $request): \Illuminate\Http\JsonResponse
    {
        $cardReview = CardReview::query()->create($request->validated());
        return response()->json([
            'message' => 'Card review created successfully',
            'card' => CardResource::make($cardReview->card),
        ]);
    }

    public function update(UpdateCardReviewRequest $request, CardReview $cardReview): \Illuminate\Http\JsonResponse
    {
        $cardReview->update($request->validated());
        return response()->json([
            'message' => 'Card review updated successfully',
            'card' => CardResource::make($cardReview->card),
        ]);
    }

    public function delete(CardReview $cardReview): \Illuminate\Http\JsonResponse
    {
        $cardReview->delete();
        return response()->json([
            'message' => 'Card review deleted successfully',
        ]);
    }
}
