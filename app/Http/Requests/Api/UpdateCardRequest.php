<?php

namespace App\Http\Requests\Api;

use App\Models\Card;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateCardRequest extends StoreCardRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $card = $this->route('card');
        return $card->user_id === Auth::guard('sanctum')->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'gallery' => ['array', 'nullable'],
            'gallery.*.id' => ['nullable'],
            'gallery.*.image' => ['nullable', 'image'],
            'gallery.*.cols' => ['required_with:gallery', 'numeric'],
            'gallery.*.rows' => ['required_with:gallery', 'numeric'],
            'gallery.*.order' => ['required_with:gallery', 'numeric'],
        ]);
    }
}
