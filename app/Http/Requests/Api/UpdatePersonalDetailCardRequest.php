<?php

namespace App\Http\Requests\Api;

use App\Models\Card;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePersonalDetailCardRequest extends StorePersonalDetailCardRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $card = Card::query()->where('id', $this->input('card_id'))->first();
        return $card->user_id === Auth::guard('sanctum')->id();
    }
}
