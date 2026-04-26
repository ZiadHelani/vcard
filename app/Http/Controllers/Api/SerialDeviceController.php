<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckSerialDeviceRequest;
use App\Http\Requests\Api\LinkDeviceRequest;
use App\Models\Card;
use App\Models\SerialDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SerialDeviceController extends Controller
{
    public function checkDevice(CheckSerialDeviceRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $serialDevice = SerialDevice::query()->where('serial_number', $data['serial_number'])->first();
        return response()->json([
            'is_available' => !$serialDevice->is_active,
            'type' => $serialDevice->type,
            'serial_number' => $serialDevice->serial_number,
        ]);
    }

    public function linkDevice(LinkDeviceRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $user = Auth::guard('sanctum')->user();
        $card = Card::query()->where('id', $data['card_id'])->first();
        if ($card->user_id !== $user?->id) {
            return response()->json([
                'message' => "This card doesn't belongs to you!",
            ], 400);
        }
        $serialDevice = SerialDevice::query()->where('serial_number', $data['serial_number'])->first();
        if ($serialDevice->isActive()) {
            return response()->json([
                'message' => "This Card isn't available",
            ], 400);
        }
        $serialDevice->user_id = $user->id;
        $serialDevice->card_id = $card->id;
        $serialDevice->is_active = true;
        $serialDevice->save();
        return response()->json([
            'message' => "Linked Successfully",
        ]);

    }
}
