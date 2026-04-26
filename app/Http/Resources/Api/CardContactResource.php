<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardContactResource extends JsonResource
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
            'title' => $this->title,
            'company' => $this->company,
            'is_active' => $this->is_active,
            'department' => $this->department,
            'image' => $this->image,
            'logo' => $this->logo,
            'emails' => ContactEmailResource::collection($this->emails),
            'phones' => ContactPhoneResource::collection($this->phones),
            'created_at' => $this->created_at,
        ];
    }
}
