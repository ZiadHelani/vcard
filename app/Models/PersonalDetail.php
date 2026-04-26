<?php

namespace App\Models;

use App\Helpers\MediaCollectionHelper;
use App\Traits\MustHaveMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class PersonalDetail extends Model implements HasMedia
{
    use MustHaveMedia;

    public $table = 'personal_details';
    protected $fillable = [
        'card_id',
        'name',
        'phone',
        'bio',
        'about',
        'conclusion',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function card(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    public function getLogoAttribute(): string
    {
        return $this->getFirstMediaUrl(MediaCollectionHelper::PERSONAL_DETAILS_LOGO);
    }

    public function getCoverAttribute(): string
    {
        return $this->getFirstMediaUrl(MediaCollectionHelper::PERSONAL_DETAILS_COVER);
    }

    public function getBackgroundImageAttribute()
    {
        $imageUrl = $this->getFirstMediaUrl(MediaCollectionHelper::PERSONAL_BACKGROUND_IMAGE);
        if (trim($imageUrl) === "" || is_null($imageUrl)) {
            return null;
        }
        return $imageUrl;
    }

    public function getContactDetailsBackgroundAttribute()
    {
        $imageUrl = $this->getFirstMediaUrl(MediaCollectionHelper::CONTACT_DETAILS_BACKGROUND);
        if (trim($imageUrl) === "" || is_null($imageUrl)) {
            return null;
        }
        return $imageUrl;
    }

    public function getFallbackMediaUrl(string $collectionName, string $conversionName = ''): null
    {
        if (MediaCollectionHelper::PERSONAL_BACKGROUND_IMAGE || MediaCollectionHelper::CONTACT_DETAILS_BACKGROUND) {
            return null;
        }
        return asset('defaults/placeholder.jpg');
    }
}
