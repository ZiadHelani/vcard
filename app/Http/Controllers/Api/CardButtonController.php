<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCardButtonRequest;
use App\Http\Requests\Api\UpdateCardButtonRequest;
use App\Http\Resources\Api\CardResource;
use App\Models\CardButton;
use Illuminate\Http\Request;

class CardButtonController extends Controller
{
    public function store(StoreCardButtonRequest $request): \Illuminate\Http\JsonResponse
    {
        $cardButton = CardButton::query()->create($request->validated());
        return response()->json([
            'message' => 'Card button created successfully',
            'card' => CardResource::make($cardButton->card),
        ]);
    }

    public function update(UpdateCardButtonRequest $request, CardButton $cardButton): \Illuminate\Http\JsonResponse
    {
        $cardButton->update($request->validated());
        return response()->json([
            'message' => 'Card button updated successfully',
            'card' => CardResource::make($cardButton->card),
        ]);
    }

    public function delete(CardButton $cardButton)
    {
        $cardButton->delete();
        return response()->json([
            'message' => 'Card button deleted successfully',
        ]);
    }
}
