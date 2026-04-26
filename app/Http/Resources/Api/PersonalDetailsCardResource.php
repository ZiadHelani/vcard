<?php

namespace App\Http\Resources\Api;

use App\Helpers\MediaCollectionHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonalDetailsCardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $profileImage = $this->getFirstMedia(MediaCollectionHelper::PERSONAL_DETAILS_LOGO);
        $profileImageId = $profileImage ? $profileImage->id : null;

        $coverImage = $this->getFirstMedia(MediaCollectionHelper::PERSONAL_DETAILS_COVER);
        $coverImageId = $coverImage ? $coverImage->id : null;

        $backgroundImage = $this->getFirstMedia(MediaCollectionHelper::PERSONAL_BACKGROUND_IMAGE);
        $backgroundImageId = $backgroundImage ? $backgroundImage->id : null;

        $contactImage = $this->getFirstMedia(MediaCollectionHelper::CONTACT_DETAILS_BACKGROUND);
        $contactImageId = $contactImage ? $contactImage->id : null;
        return [
//            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'bio' => $this->bio,
            'about' => $this->about,
            'conclusion' => $this->conclusion,
            'profile_image' => $this->logo,
            'profile_image_id' => $profileImageId,
            'cover_image' => $this->cover,
            'cover_image_id' => $coverImageId,
            'background_image' => $this->background_image,
            'background_image_id' => $backgroundImageId,
            'contact_details_background' => $this->contact_details_background,
            'contact_details_background_id' => $contactImageId,
        ];
    }
}
