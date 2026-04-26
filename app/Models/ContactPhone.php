<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactPhone extends Model
{
    public $table = 'contact_phones';
    protected $fillable = [
        'contact_id',
        'phone',
        'ext',
        'label',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function contact(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }
}
