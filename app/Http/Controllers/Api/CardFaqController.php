<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCardFaqRequest;
use App\Http\Requests\Api\UpdateCardFaqRequest;
use App\Http\Resources\Api\CardResource;
use App\Models\CardFaq;
use Illuminate\Http\Request;

class CardFaqController extends Controller
{
    public function store(StoreCardFaqRequest $request): \Illuminate\Http\JsonResponse
    {
        $cardFaq = CardFaq::query()->create($request->validated());
        return response()->json([
            'message' => 'Card faq created successfully',
            'card' => CardResource::make($cardFaq->card),
        ]);
    }

    public function update(UpdateCardFaqRequest $request, CardFaq $cardFaq): \Illuminate\Http\JsonResponse
    {
        $cardFaq->update($request->validated());
        return response()->json([
            'message' => 'Card faq updated successfully',
            'card' => CardResource::make($cardFaq->card),
        ]);
    }

    public function delete(CardFaq $cardFaq): \Illuminate\Http\JsonResponse
    {
        $cardFaq->delete();
        return response()->json([
            'message' => 'Card faq deleted successfully',
        ]);
    }
}
