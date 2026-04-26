<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactEmail extends Model
{
    public $table = 'contact_emails';
    protected $fillable = [
        'email',
        'contact_id',
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
