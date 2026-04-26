<?php

namespace App\Models;

use App\Helpers\MediaCollectionHelper;
use App\Traits\MustHaveMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class CardGallery extends Model implements HasMedia
{
    use MustHaveMedia;

    public $table = 'card_galleries';
    protected $fillable = [
        'card_id',
        'rows',
        'cols',
        'order',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'rows' => 'integer',
        'cols' => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model) {
            if (empty($model->order)) {
                $maxOrder = self::where('card_id', $model->card_id)->max('order');
                $model->order = $maxOrder ? $maxOrder + 1 : 1;
            }
        });
    }

    public function card(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Card::class, 'card_id');
    }

    public function getImageAttribute(): string
    {
        return $this->getFirstMediaUrl(MediaCollectionHelper::CARD_GALLERY);
    }
}
