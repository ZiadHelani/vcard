<?php

namespace App\Http\Resources;

use App\Http\Resources\Api\SubscriptionResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'is_verified' => $this->hasVerifiedEmail(),
            'has_active_subscription' => $this->hasActiveSubscription(),
            'active_subscription_now' => SubscriptionResource::make($this->activeSubscriptionNow()),
            'image' => $this->image,
            'created_at' => $this->created_at,
        ];
    }
}
