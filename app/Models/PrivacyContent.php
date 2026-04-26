<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PrivacyContent extends Model
{
    use HasTranslations;

    public $table = 'privacy_contents';
    public array $translatable = [
        'privacy_policy',
        'terms_of_use',
    ];
    protected $fillable = [
        'privacy_policy',
        'terms_of_use',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
