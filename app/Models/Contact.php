<?php

namespace App\Models;

use App\Helpers\MediaCollectionHelper;
use App\Traits\MustHaveMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class Contact extends Model implements HasMedia
{
    use MustHaveMedia;

    public $table = 'contacts';
    protected $fillable = [
        'user_id',
        'name',
        'title',
        'company',
        'is_active',
        'associated_card_id',
        'department',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function card(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Card::class, 'associated_card_id');
    }

    public function phones(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ContactPhone::class);
    }

    public function emails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ContactEmail::class);
    }

    public function getImageAttribute(): string
    {
        return $this->getFirstMediaUrl(MediaCollectionHelper::CONTACT_IMAGE);
    }

    public function getLogoAttribute(): string
    {
        return $this->getFirstMediaUrl(MediaCollectionHelper::CONTACT_LOGO);
    }
}
