<?php

namespace App\Traits;

use App\Helpers\MediaCollectionHelper;
use Spatie\MediaLibrary\InteractsWithMedia;

trait MustHaveMedia
{
    use InteractsWithMedia;

    public function getFallbackMediaUrl(string $collectionName = 'default', string $conversionName = ''): string
    {
        return MediaCollectionHelper::getPlaceholderPath();
    }


    // protected static function booted()
    // {
    //     static::created(function ($model) {
    //         if (!$model->hasMedia($model->getCollection())) {
    //             $model->addMediaFromUrl(MediaCollectionHelper::getPlaceholderPath())
    //                 ->preservingOriginal()
    //                 ->toMediaCollection($model->getCollection());
    //         }
    //     });
    // }
}
