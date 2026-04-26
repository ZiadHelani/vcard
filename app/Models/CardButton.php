<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardButton extends Model
{
    public $table = 'card_buttons';
    protected $fillable = [
        'card_id',
        'title',
        'icon',
        'link',
        'font_family',
        'font_size',
        'order',
        'color',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
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
        return $this->belongsTo(Card::class);
    }
}
