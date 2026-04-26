<?php

namespace App\Helpers;

class MediaCollectionHelper
{
    public static function getPlaceholderPath(): string
    {
        return config('app.url') . '/defaults/placeholder.jpg';
    }

    public const PROFILE = 'profile';
    public const PERSONAL_DETAILS_LOGO = 'personal_details_logo';
    public const PERSONAL_DETAILS_COVER = 'personal_details_cover';
    public const PERSONAL_BACKGROUND_IMAGE = 'personal_background_image';
    public const CONTACT_IMAGE = 'contact_image';
    public const CONTACT_LOGO = 'contact_logo';
    public const CARD_GALLERY = 'card_gallery';
    public const CONTACT_DETAILS_BACKGROUND = 'contact_details_background';
}
