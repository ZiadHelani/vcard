<?php

namespace App\Http\Resources\Api;

use App\Helpers\MediaCollectionHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardGalleryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $image = $this->getFirstMedia(MediaCollectionHelper::CARD_GALLERY);
        $imageId = $image ? $image->id : null;
        return [
            'id' => $this->id,
            'rows' => $this->rows,
            'cols' => $this->cols,
            'src_id' => $imageId,
            'src' => $this->image,
            'order' => $this->order,
        ];
    }
}
