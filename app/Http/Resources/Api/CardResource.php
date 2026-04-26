<?php

namespace App\Http\Resources\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
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
            'uuid' => $this->uuid,
            'slug' => $this->slug,
            'name' => $this->name,
            'type' => $this->type,
            'color' => $this->color,
            'contact_button_color' => $this->contact_button_color,
            'total_views' => $this->total_views,
            'total_saves' => $this->total_saves,
            'qrcode' => $this->qrcode,
            'qrcode_logo' => $this->qrcode_logo,
            'qrcode_logo_path' => $this->qrcode_logo,
            'pro_mode' => $this->pro_mode,
            'published' => $this->published,
            'link' => "https://ultratech.co.il/card/{$this->slug}/preview",
            'personal_details' => PersonalDetailsCardResource::make($this->personalDetails),
            'faqs' => CardFaqResource::collection($this->whenLoaded('faqs')),
            'reviews' => CardReviewResource::collection($this->whenLoaded('reviews')),
            'buttons' => CardButtonResource::collection($this->whenLoaded('buttons')),
            'gallery' => CardGalleryResource::collection($this->whenLoaded('gallery')),
            'contacts' => CardContactResource::collection($this->whenLoaded('contacts')),
            'created_at' => Carbon::parse($this->created_at)->diffForHumans(),
            'updated_at' => Carbon::parse($this->updated_at)->diffForHumans(),
        ];
    }
}
