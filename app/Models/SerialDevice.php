<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SerialDevice extends Model
{
    public $table = 'serial_devices';
    protected $fillable = [
        'id',
        'type',
        'serial_number',
        'user_id',
        'is_active',
        'from_old',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'from_old' => 'boolean',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }
}
