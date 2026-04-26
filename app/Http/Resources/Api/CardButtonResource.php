<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class CardButtonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $icon = $this->icon;
        if (Str::contains($icon, 'email')) {
            if (Str::contains($icon, 'mailto:')) {
                $this->link = str_replace("mailto:", "", $icon);
                $this->link = "mailto:{$this->link}";
            } else {
                $this->link = "mailto:{$this->link}";
            }
        }
        if (Str::contains($icon, 'phone')) {
            if (Str::contains($icon, 'tel:')) {
                $this->link = str_replace("tel:", "", $icon);
                $this->link = "tel:{$this->link}";
            } else {
                $this->link = "tel:{$this->link}";
            }
        }
        return [
            'id' => $this->id,
            'title' => $this->title,
            'icon' => $this->icon,
            'link' => $this->link,
            'font_family' => $this->font_family,
            'font_size' => $this->font_size,
            'color' => $this->color,
            'order' => $this->order
        ];
    }
}
