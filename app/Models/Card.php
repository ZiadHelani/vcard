<?php

namespace App\Models;

use App\Helpers\MediaCollectionHelper;
use App\Traits\MustHaveMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Card extends Model implements HasMedia
{
    use MustHaveMedia, HasSlug;

    public $table = 'cards';
    protected $fillable = [
        'user_id',
        'uuid',
        'name',
        'type',
        'color',
        'contact_button_color',
        'published',
        'total_views',
        'total_saves',
        'qrcode',
        'qrcode_logo',
        'pro_mode',
        'slug',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'total_views' => 'integer',
        'total_saves' => 'integer',
        'published' => 'boolean',
        'uuid' => 'string',
        'pro_mode' => 'boolean',
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    protected static function booted(): void
    {
        static::creating(static function ($card) {
            if (empty($card->uuid)) {
                $card->uuid = (string)Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function faqs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CardFaq::class);
    }

    public function contacts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Contact::class, 'associated_card_id', 'id');
    }

    public function personalDetails(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PersonalDetail::class);
    }

    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CardReview::class);
    }

    public function buttons(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CardButton::class)->orderBy('order');
    }

    public function gallery(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CardGallery::class)->orderBy('order');
    }

    public function isProMode(): bool
    {
        return $this->pro_mode;
    }
}
