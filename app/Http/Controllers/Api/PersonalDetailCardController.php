<?php

namespace App\Http\Controllers\Api;

use App\Helpers\MediaCollectionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StorePersonalDetailCardRequest;
use App\Http\Requests\Api\UpdatePersonalDetailCardRequest;
use App\Http\Resources\Api\CardResource;
use App\Models\Card;
use Illuminate\Http\Request;

class PersonalDetailCardController extends Controller
{
    public function store(StorePersonalDetailCardRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->except('logo', 'cover');
        $card = Card::query()->where('id', $data['card_id'])->first();
        $personalDetail = $card->personalDetails()->create($data);
        if ($request->hasFile('logo')) {
            $personalDetail->addMediaFromRequest('logo')->toMediaCollection(MediaCollectionHelper::PERSONAL_DETAILS_LOGO);
        }
        if ($request->hasFile('cover')) {
            $personalDetail->addMediaFromRequest('cover')->toMediaCollection(MediaCollectionHelper::PERSONAL_DETAILS_COVER);
        }
        return response()->json([
            'message' => 'Personal details card created successfully',
            'card' => CardResource::make($card),
        ]);
    }

    public function update(UpdatePersonalDetailCardRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->except('logo', 'cover');
        $card = Card::query()->where('id', $data['card_id'])->first();
        $card->personalDetails()->update($data);
        if ($request->hasFile('logo')) {
            $card->personalDetails->clearMediaCollection(MediaCollectionHelper::PERSONAL_DETAILS_LOGO);
            $card->personalDetails->addMediaFromRequest('logo')->toMediaCollection(MediaCollectionHelper::PERSONAL_DETAILS_LOGO);
        }
        if ($request->hasFile('cover')) {
            $card->personalDetails->clearMediaCollection(MediaCollectionHelper::PERSONAL_DETAILS_COVER);
            $card->personalDetails->addMediaFromRequest('cover')->toMediaCollection(MediaCollectionHelper::PERSONAL_DETAILS_COVER);
        }
        return response()->json([
            'message' => 'Personal details card updated successfully',
            'card' => CardResource::make($card),
        ]);
    }
}
